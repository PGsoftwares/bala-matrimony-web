<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    protected FCMController $fcmController;
    protected ?string $razorpayKey;
    protected ?string $razorpaySecret;

    public function __construct(FCMController $fcmController)
    {
        $this->fcmController = $fcmController;
        $this->razorpayKey = config('services.razorpay.key') ?: env('RAZORPAY_KEY');
        $this->razorpaySecret = config('services.razorpay.secret') ?: env('RAZORPAY_SECRET');
    }

    public function getPackages(): JsonResponse
    {
        $packages = DB::table('packages')->get();
        return response()->json([
            'status' => true,
            'packages' => $packages
        ]);
    }

    /**
     * Create a Razorpay Order for Mobile / API Client
     */
    public function createRazorpayOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
            'package' => 'required|string',
            'month' => 'nullable|integer',
            'no_of_contact' => 'nullable|integer',
            'no_of_chat' => 'nullable|integer',
            'no_of_chats' => 'nullable|integer',
            'no_of_interest' => 'nullable|integer',
            'no_of_interests' => 'nullable|integer',
        ]);

        $userId = $validated['user_id'];
        $month = (int) $request->input('month', 0);
        $package = $request->input('package');
        $amount = (float) $request->input('amount');

        // Fetch package limits from packages table if not provided
        $pkgInfo = DB::table('packages')->where('name', $package)->first();
        $noOfContact = (int) $request->input('no_of_contact', $pkgInfo->no_of_contact ?? 0);
        $noOfChat = (int) $request->input('no_of_chats', $pkgInfo->no_of_chats ?? 0);
        $noOfInterest = (int) $request->input('no_of_interests', $pkgInfo->no_of_interests ?? 0);

        try {
            $api = new Api($this->razorpayKey, $this->razorpaySecret);
            $merchantReceiptId = 'REC_' . date('YmdHis') . '_' . $userId;

            $orderData = [
                'receipt' => $merchantReceiptId,
                'amount' => (int) round($amount * 100), // paise
                'currency' => 'INR',
                'notes' => [
                    'user_id' => (string) $userId,
                    'package' => (string) $package,
                    'month' => (string) $month,
                ]
            ];

            $razorpayOrder = $api->order->create($orderData);

            $now = Carbon::now();
            $expiryDate = $month > 0 ? $now->copy()->addMonths($month)->toDateTimeString() : null;

            // Insert pending record in receipts
            $receiptId = DB::table('receipts')->insertGetId([
                'user_id' => $userId,
                'amount' => $amount,
                'package' => $package,
                'order_id' => $razorpayOrder['id'],
                'month' => $month,
                'no_of_contact' => $noOfContact,
                'no_of_viewed' => 0,
                'balance' => $noOfContact,
                'no_of_chats' => $noOfChat,
                'viewed_chats' => 0,
                'balance_chats' => $noOfChat,
                'no_of_interests' => $noOfInterest,
                'viewed_interests' => 0,
                'balance_interests' => $noOfInterest,
                'recharge_date' => $now->toDateTimeString(),
                'expiry_date' => $expiryDate,
                'paid_by' => 'online',
                'payment_method' => 'razorpay',
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Razorpay order created successfully.',
                'order_id' => $razorpayOrder['id'],
                'amount' => (int) round($amount * 100),
                'amount_rupees' => $amount,
                'currency' => 'INR',
                'razorpay_key' => $this->razorpayKey,
                'receipt_id' => $receiptId,
                'order_details' => $razorpayOrder->toArray(),
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Razorpay create order error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => 'Failed to create Razorpay order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify Razorpay Payment for Mobile / API Client
     */
    public function verifyRazorpayPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $orderId = $validated['razorpay_order_id'];
        $paymentId = $validated['razorpay_payment_id'];
        $signature = $request->input('razorpay_signature');

        $isVerified = false;

        $api = new Api($this->razorpayKey, $this->razorpaySecret);

        if (!empty($signature)) {
            try {
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id' => $orderId,
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature' => $signature,
                ]);
                $isVerified = true;
            } catch (\Exception $e) {
                Log::warning('API signature verification exception: ' . $e->getMessage() . '. Checking direct payment fetch...');
            }
        }

        // Fallback: Verify directly via Razorpay API
        if (!$isVerified) {
            try {
                $payment = $api->payment->fetch($paymentId);
                if ($payment && in_array($payment->status, ['captured', 'authorized']) && $payment->order_id === $orderId) {
                    $isVerified = true;
                    Log::info('API payment verified via Razorpay API fetch: ' . $paymentId);
                }
            } catch (\Exception $fetchEx) {
                Log::error('API Razorpay fetch payment error: ' . $fetchEx->getMessage());
            }
        }

        if (!$isVerified) {
            DB::table('receipts')->where('order_id', $orderId)->update([
                'status' => 'failed',
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => false,
                'error' => 'Payment verification failed.',
            ], 400);
        }

        $receipt = DB::table('receipts')->where('order_id', $orderId)->first();

        if (!$receipt) {
            return response()->json([
                'status' => false,
                'error' => 'No matching receipt found for order ID.',
            ], 404);
        }

        $pkg = DB::table('packages')->where('name', $receipt->package)->first();
        $month = (int) ($receipt->month !== null ? $receipt->month : ($pkg->month ?? 0));
        $noOfContact = (int) ($receipt->no_of_contact ?: ($pkg->no_of_contact ?? 0));
        $noOfChats = (int) ($receipt->no_of_chats ?: ($pkg->no_of_chats ?? 0));
        $noOfInterests = (int) ($receipt->no_of_interests ?: ($pkg->no_of_interests ?? 0));

        $now = Carbon::now();
        $expiryDate = $month > 0 ? $now->copy()->addMonths($month)->toDateTimeString() : null;

        // Update current receipt to paid
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
            'recharge_date' => $now->toDateTimeString(),
            'expiry_date' => $expiryDate,
            'updated_at' => $now,
        ]);

        // Close previously active packages for this user
        DB::table('receipts')
            ->where('user_id', $receipt->user_id)
            ->where('id', '!=', $receipt->id)
            ->where('status', 'paid')
            ->update([
                'status' => 'closed',
                'updated_at' => $now,
            ]);

        // Notify admin
        $adminUser = DB::table('users')->where('role', 'admin')->first();
        if ($adminUser && !empty($adminUser->device_token)) {
            $user = DB::table('users')->where('id', $receipt->user_id)->first();
            $title = 'Package Upgraded';
            $body = "User " . ($user->name ?? 'Member') . " subscribed to " . $receipt->package . " via Razorpay!";
            try {
                $this->fcmController->sendFcmNotificationHelper($adminUser->device_token, $title, $body);
            } catch (\Exception $e) {
                Log::warning('FCM error: ' . $e->getMessage());
            }
        }

        $updatedReceipt = DB::table('receipts')->where('id', $receipt->id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Payment verified and package activated successfully.',
            'payment_details' => $updatedReceipt,
        ], 200);
    }

    /**
     * User payment history
     */
    public function paymentHistory(Request $request): JsonResponse
    {
        $userId = $request->input('user_id') ?? auth()->id();

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User ID is required.',
            ], 400);
        }

        $receipts = DB::table('receipts')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Payment history fetched successfully.',
            'payment_history' => $receipts,
        ], 200);
    }
}
