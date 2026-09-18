<?php

namespace App\Http\Controllers;

use App\Services\PhonePeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PhonePeController extends Controller
{
    protected PhonePeService $phonePe;

    public function __construct(PhonePeService $phonePe)
    {
        $this->phonePe = $phonePe;
    }

    public function checkoutPhonePe(Request $request): View
    {
        $amount = $request->input('amount');
        $userId = $request->input('user_id');
        $package = $request->input('package');
        $month = (int) $request->input('month');
        $no_of_contact = $request->input('no_of_contact');
        $no_of_chats = $request->input('no_of_chats');
        $no_of_interests = $request->input('no_of_interests');
        return view('web.checkoutPhonePe', compact('amount', 'userId', 'package', 'month', 'no_of_contact', 'no_of_chats', 'no_of_interests'));
    }

    public function createOrder(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $accessToken = $this->phonePe->getAuthToken();
            if (!$accessToken) {
                return response()->json(['error' => 'Failed to fetch auth token'], 500);
            }
            session(['access_token' => $accessToken]);

            /*---  Prepare Order Data ---*/
            $amountInRupees = $request->input('amount');
            $amountInPaisa  = $amountInRupees * 100;
            $merchantOrderId = 'PG' . date('YmdHis');

            $userId        = $request->input('user_id');
            $package       = $request->input('package');
            $month         = (int) $request->input('month');
            $noOfContact   = $request->input('no_of_contact');
            $noOfChat      = $request->input('no_of_chats');
            $noOfInterests = $request->input('no_of_interests');

            /*--- Save Receipt ---*/
            DB::table('receipts')->updateOrInsert(
                ['order_id' => $merchantOrderId],
                [
                    'user_id' => $userId,
                    'amount' => $amountInRupees,
                    'package' => $package,
                    'month' => $month,
                    'no_of_contact' => $noOfContact,
                    'no_of_viewed' => 0,
                    'balance' => $noOfContact,
                    'no_of_chats' => $noOfChat,
                    'viewed_chats' => 0,
                    'balance_chats' => $noOfChat,
                    'no_of_interests' => $noOfInterests,
                    'viewed_interests' => 0,
                    'balance_interests' => $noOfInterests,
                    'recharge_date' => now(),
                    'expiry_date' => $month > 0 ? now()->addMonths((int)$month) : null,
                    'status' => 'pending',
                    'payment_method' => 'online',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            /*--- Create Payment Order ---*/
            $orderPayload = [
                'merchantOrderId' => $merchantOrderId,
                'amount' => $amountInPaisa,
                'metaInfo' => [
                    'udf1' => 'userID: ' . $userId,
                    'udf2' => 'Package: ' . $package,
                    'udf3' => 'Month: ' . $month,
                    'udf4' => 'Chat: ' . $noOfChat,
                    'udf5' => 'Interest: ' . $noOfInterests,
                ],
                'paymentFlow' => [
                    'type' => 'PG_CHECKOUT',
                    'merchantUrls' => [
                        'redirectUrl' => route('callbackPhonePe') . '?merchantOrderId=' . $merchantOrderId,
                    ],
                ],
            ];

            $orderResponse = $this->phonePe->createOrder($accessToken, $orderPayload);

            if (!$orderResponse->successful()) {
                return response()->json(['error' => 'Failed to create payment order'], 500);
            }

            $redirectUrl = $orderResponse->json()['redirectUrl'] ?? null;
            return $redirectUrl
                ? redirect($redirectUrl)
                : response()->json(['error' => 'Redirect URL not found'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong : ' . $e->getMessage()], 500);
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $merchantOrderId = $request->query('merchantOrderId');
        $accessToken     = session('access_token');

        if (!$merchantOrderId || !$accessToken) {
            return redirect('plan')->with('error', 'Missing order details or session expired.');
        }

        $orderStatusResponse = $this->phonePe->checkOrderStatus($accessToken, $merchantOrderId);

        if (!$orderStatusResponse->successful()) {
            return redirect('plan')->with('error', 'Failed to fetch order status.');
        }

        $state = $orderStatusResponse->json('state');
        $statusMap = [
            'COMPLETED' => 'paid',
            'FAILED'    => 'failed',
            'PENDING'   => 'pending',
        ];
        $receiptStatus = $statusMap[$state] ?? 'pending';

        DB::table('receipts')
            ->where('order_id', $merchantOrderId)
            ->update(['status' => $receiptStatus]);

        if ($state === 'COMPLETED') {
            $receipt = DB::table('receipts')->where('order_id', $merchantOrderId)->first();

            DB::table('receipts')
                ->where('user_id', $receipt->user_id)
                ->where('status', 'paid')
                ->where('order_id', '!=', $merchantOrderId)
                ->update(['status' => 'closed']);

            return redirect('plan')->with('success', 'Payment successful!');
        }

        if ($state === 'PENDING') {
            return redirect('plan')->with('warning', 'Payment is pending. Kindly check later.');
        }

        return redirect('plan')->with('error', 'Payment failed or was cancelled.');
    }

    public function phonePeHistory(): View
    {
        return view('web.phonePeHistory', ['user_id' => auth()->id()]);
    }
}
