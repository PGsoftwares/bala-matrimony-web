<?php

namespace App\Http\Controllers\Packages;

use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataSharedController;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    protected ?string $razorpayKey;
    protected ?string $razorpaySecret;
    protected FCMController $fcmController;

    public function __construct(FCMController $fcmController)
    {
        $this->razorpayKey = config('services.razorpay.key') ?: env('RAZORPAY_KEY');
        $this->razorpaySecret = config('services.razorpay.secret') ?: env('RAZORPAY_SECRET');
        $this->fcmController = $fcmController;
    }

    // Create a new Razorpay order and show checkout page
    public function razorPayment(Request $request): View|RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'package' => 'required|string',
            'month' => 'nullable|integer|min:0',
        ]);

        $amount = $request->input('amount');
        $userId = $request->input('user_id', Auth::id());
        $package = $request->input('package');
        $month = (int) $request->input('month', 0);

        // Look up package defaults if limits not passed
        $pkgInfo = DB::table('packages')->where('name', $package)->first();
        $no_of_contact = (int) $request->input('no_of_contact', $pkgInfo->no_of_contact ?? 0);
        $no_of_chats = (int) $request->input('no_of_chats', $pkgInfo->no_of_chats ?? 0);
        $no_of_interests = (int) $request->input('no_of_interests', $pkgInfo->no_of_interests ?? 0);

        try {
            // Initialize Razorpay API
            $api = new Api($this->razorpayKey, $this->razorpaySecret);

            $merchantReceiptId = 'REC_' . date('YmdHis') . '_' . $userId;

            // Create Razorpay order
            $orderData = [
                'receipt' => $merchantReceiptId,
                'amount' => (int) round($amount * 100), // in paise
                'currency' => 'INR',
                'notes' => [
                    'user_id' => (string) $userId,
                    'package' => (string) $package,
                    'month' => (string) $month,
                ]
            ];

            $razorpayOrder = $api->order->create($orderData);

            // Save pending entry to receipts table
            $now = Carbon::now();
            $rechargeDate = $now->toDateTimeString();
            $expiryDate = $month > 0 ? $now->copy()->addMonths($month)->toDateTimeString() : null;

            DB::table('receipts')->insert([
                'user_id' => $userId,
                'package' => $package,
                'amount' => $amount,
                'order_id' => $razorpayOrder['id'],
                'month' => $month,
                'no_of_contact' => $no_of_contact,
                'no_of_viewed' => 0,
                'balance' => $no_of_contact,
                'no_of_chats' => $no_of_chats,
                'viewed_chats' => 0,
                'balance_chats' => $no_of_chats,
                'no_of_interests' => $no_of_interests,
                'viewed_interests' => 0,
                'balance_interests' => $no_of_interests,
                'paid_by' => 'online',
                'payment_method' => 'razorpay',
                'recharge_date' => $rechargeDate,
                'expiry_date' => $expiryDate,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $user = Auth::user();
            $metaTags = DataSharedController::MetaData('payment');

            // Prepare data for the view
            $data = [
                'order_id' => $razorpayOrder['id'],
                'amount' => (int) round($amount * 100),
                'amount_rupees' => $amount,
                'name' => $user->name ?? 'User',
                'email' => $user->email ?? '',
                'contact' => $user->mobile ?? '',
                'package' => $package,
                'month' => $month,
                'no_of_contact' => $no_of_contact,
                'no_of_chats' => $no_of_chats,
                'no_of_interests' => $no_of_interests,
                'razorpayKey' => $this->razorpayKey,
            ];

            return view('web.payment', compact('data', 'metaTags'));

        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return redirect('payment-plans')->with('error', 'Unable to initiate payment: ' . $e->getMessage());
        }
    }

    // Verify the payment success callback
    public function paymentSuccess(Request $request): RedirectResponse
    {
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if (!$razorpayOrderId || !$razorpayPaymentId) {
            return redirect('plan')->with('error', 'Incomplete payment credentials received.');
        }

        $signatureStatus = $this->verifySignature([
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $razorpaySignature
        ]);

        if ($signatureStatus) {
            $receipt = DB::table('receipts')->where('order_id', $razorpayOrderId)->first();

            if ($receipt) {
                $pkg = DB::table('packages')->where('name', $receipt->package)->first();
                $month = (int) ($receipt->month !== null ? $receipt->month : ($pkg->month ?? 0));
                $noOfContact = (int) ($receipt->no_of_contact ?: ($pkg->no_of_contact ?? 0));
                $noOfChats = (int) ($receipt->no_of_chats ?: ($pkg->no_of_chats ?? 0));
                $noOfInterests = (int) ($receipt->no_of_interests ?: ($pkg->no_of_interests ?? 0));

                $rechargeDate = Carbon::now();
                $expiryDate = $month > 0 ? $rechargeDate->copy()->addMonths($month)->toDateTimeString() : null;

                // Update current receipt status to 'paid'
                DB::table('receipts')->where('id', $receipt->id)->update([
                    'status' => 'paid',
                    'paid_by' => 'online',
                    'payment_method' => 'razorpay',
                    'month' => $month,
                    'no_of_contact' => $noOfContact,
                    'balance' => $noOfContact,
                    'no_of_chats' => $noOfChats,
                    'balance_chats' => $noOfChats,
                    'no_of_interests' => $noOfInterests,
                    'balance_interests' => $noOfInterests,
                    'recharge_date' => $rechargeDate->toDateTimeString(),
                    'expiry_date' => $expiryDate,
                    'updated_at' => now(),
                ]);

                // Close any previously active packages for this user
                DB::table('receipts')
                    ->where('user_id', $receipt->user_id)
                    ->where('id', '!=', $receipt->id)
                    ->where('status', 'paid')
                    ->update([
                        'status' => 'closed',
                        'updated_at' => now(),
                    ]);

                // Notify admin
                $adminUser = DB::table('users')->where('role', 'admin')->first();
                if ($adminUser && !empty($adminUser->device_token)) {
                    $title = 'Package Upgraded';
                    $body = "User " . (Auth::user()->name ?? 'Member') . " has subscribed to the " . $receipt->package . " package via Razorpay!";
                    try {
                        $this->fcmController->sendFcmNotificationHelper($adminUser->device_token, $title, $body);
                    } catch (\Exception $e) {
                        Log::warning('FCM notification error: ' . $e->getMessage());
                    }
                }

                return redirect('plan')->with('success', 'Payment Successful! Your membership package has been activated.');
            }

            return redirect('plan')->with('success', 'Payment Successful');
        } else {
            // Mark receipt as failed
            DB::table('receipts')->where('order_id', $razorpayOrderId)->update([
                'status' => 'failed',
                'updated_at' => now(),
            ]);

            return redirect('plan')->with('error', 'Payment Verification Failed. If money was debited, please contact support.');
        }
    }

    // Helper method to verify Razorpay payment signature
    private function verifySignature(array $data): bool
    {
        $api = new Api($this->razorpayKey, $this->razorpaySecret);

        if (!empty($data['razorpay_signature'])) {
            $attributes = [
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature']
            ];

            try {
                $api->utility->verifyPaymentSignature($attributes);
                return true;
            } catch (\Exception $e) {
                Log::warning('Razorpay signature verification exception: ' . $e->getMessage() . '. Checking direct payment status...');
            }
        }

        // Fallback: Verify payment directly with Razorpay API
        try {
            $payment = $api->payment->fetch($data['razorpay_payment_id']);
            if ($payment && in_array($payment->status, ['captured', 'authorized']) && $payment->order_id === $data['razorpay_order_id']) {
                Log::info('Razorpay payment verified via direct API fetch: ' . $data['razorpay_payment_id']);
                return true;
            }
        } catch (\Exception $fetchEx) {
            Log::error('Razorpay API fetch payment error: ' . $fetchEx->getMessage());
        }

        return false;
    }
}
