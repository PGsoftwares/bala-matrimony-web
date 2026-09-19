<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PhonePeController extends Controller
{
    public function fetchAuthToken(Request $request): JsonResponse
    {
        $clientId = env('PHONEPE_CLIENT_ID');
        $clientSecret = env('PHONEPE_CLIENT_SECRET');
        $clientVersion = env('PHONEPE_CLIENT_VERSION');

        $response = Http::asForm()->post(env('PHONEPE_URL') . '/v1/oauth/token', [
            'client_id' => $clientId,
            'client_version' => $clientVersion,
            'client_secret' => $clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function createOrder(Request $request): JsonResponse
    {
        $baseUrl = env('PHONEPE_URL');
        $createPayUrl = env('PHONEPE_CREATE_URL');
        // Step 1: Fetch Auth Token
        $authResponse = Http::asForm()->post("$baseUrl/v1/oauth/token", [
            'client_id' => env('PHONEPE_CLIENT_ID'),
            'client_version' => env('PHONEPE_CLIENT_VERSION'),
            'client_secret' => env('PHONEPE_CLIENT_SECRET'),
            'grant_type' => 'client_credentials',
        ]);

        if (!$authResponse->successful()) {
            return response()->json(['error' => 'Failed to fetch auth token'], 500);
        }

        $accessToken = $authResponse['access_token'];

        // Step 2: Create Order
        $merchantOrderId = 'PG' . date('YmdHis');
        $amountInRupees = $request->input('amount');
        $amountInPaisa = $amountInRupees * 100;
        $userId = $request->input('user_id');
        $package = $request->input('package');
        $month =  $request->input('month');
        $noOfContact =  $request->input('no_of_contact');
        $noOfChat =  $request->input('no_of_chat');
        $noOfInterest = $request->input('no_of_interest');

        $existingReceipt = DB::table('receipts')
            ->where('order_id', $merchantOrderId)
            ->first();

        if ($existingReceipt) {
            DB::table('receipts')
                ->where('order_id', $merchantOrderId)
                ->update([
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
                    'no_of_interests' => $noOfInterest,
                    'viewed_interests' => 0,
                    'balance_interests' => $noOfInterest,
                    'recharge_date' => now(),
                    'expiry_date' => ((int) $month > 0) ? now()->addMonths((int) $month) : null,
                    'status' => 'pending',
                    'updated_at' => now()
                ]);
        } else {
            DB::table('receipts')->insert([
                'user_id' => $userId,
                'amount' => $amountInRupees,
                'package' => $package,
                'order_id' => $merchantOrderId,
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
                'recharge_date' => now(),
                'expiry_date' => ((int) $month > 0) ? now()->addMonths((int) $month) : null,
                'status' => 'pending',
                'created_at' => now(),
            ]);
        }

        $orderPayload = [
            "merchantOrderId" => $merchantOrderId,
            "amount" => $amountInPaisa,
            "metaInfo" => [
                'udf1' => 'userId: ' . $userId,
                'udf2' => 'Package: ' . $package,
                'udf3' => 'Month: ' . $month,
                'udf4' => 'Chat: ' . $noOfChat,
                'udf5' => 'Interest: ' . $noOfInterest,
            ],
            "paymentFlow" => [
                "type" => "PG_CHECKOUT"
            ]
        ];

        $orderResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'O-Bearer ' . $accessToken,
        ])->post("$createPayUrl/checkout/v2/sdk/order", $orderPayload);

        if (!$orderResponse->successful()) {
            return response()->json(['error' => 'Failed to create order'], 500);
        }

        return response()->json([
            'merchantOrderId' => $merchantOrderId,
            'phonePe' => $orderResponse->json()
        ]);
    }

    public function phonePeCheckStatus($merchantOrderId): JsonResponse
    {
        $baseUrl = env('PHONEPE_URL');
        $statusUrl = env('PHONEPE_STATUS_URL');

        // Step 1: Fetch new Access Token
        $authResponse = Http::asForm()->post("$baseUrl/v1/oauth/token", [
            'client_id' => env('PHONEPE_CLIENT_ID'),
            'client_version' => env('PHONEPE_CLIENT_VERSION'),
            'client_secret' => env('PHONEPE_CLIENT_SECRET'),
            'grant_type' => 'client_credentials',
        ]);

        $accessToken = $authResponse['access_token'];

        // Step 2: Call the Order Status API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'O-Bearer ' . $accessToken,
        ])->get("$statusUrl/checkout/v2/order/$merchantOrderId/status");

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch order status'], 500);
        }

        $data = $response->json();

        if (isset($data['state']) && $data['state'] === 'COMPLETED') {
            DB::table('receipts')->where('order_id', $merchantOrderId)->update([
                'status' => 'paid',
            ]);
        } elseif ($data['state'] === 'FAILED') {
            DB::table('receipts')->where('order_id', $merchantOrderId)->update([
                'status' => 'failed',
            ]);
        }

        return response()->json($data);
    }

    public function paymentHistory(Request $request): JsonResponse
    {
        $userId = $request->input('user_id') ?? Auth()->user()->id;
        $userPackages = DB::table('receipts')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($userPackages->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No payment history found.',
                'payment_history' => [],
            ], 404);
        }

        $history = $userPackages->map(function ($receipt) {
            return [
                'orderId'   => $receipt->order_id,
                'package'  => $receipt->package ?? '',
                'amount'        => $receipt->amount ?? 0,
                'purchased_date' => $receipt->recharge_date,
                'payment_status' => $receipt->status,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Payment history fetched successfully.',
            'payment_history' => $history,
        ]);
    }

    public function phonePePayment(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|numeric',
            'package' => 'required|string',
            'order_id' => 'required|string',
            'month' => 'required|integer',
            'no_of_contact' => 'required|integer',
            'recharge_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'balance' => 'required|integer',
            'no_of_viewed' => 'required|integer',
            'status' => 'required|string',
            'no_of_chats' => 'required|integer',
            'viewed_chats' => 'required|integer',
            'balance_chats' => 'required|integer',
            'no_of_interests' => 'required|integer',
            'viewed_interests' => 'required|integer',
            'balance_interests' => 'required|integer',
        ]);

        if (strtolower($validatedData['status']) === 'failed') {
            DB::table('receipts')
                ->where('order_id', $validatedData['order_id'])
                ->update(['status' => 'failed']);

            return response()->json([
                'error' => 'Payment Failed',
                'status' => 'failed'
            ], 400);
        }

        DB::table('receipts')
            ->where('user_id', $validatedData['user_id'])
            ->where('status', '!=', 'failed')
            ->update(['status' => 'closed']);

        $payDetails = [
            'amount'            => $validatedData['amount'],
            'package'           => $validatedData['package'],
            'month'             => $validatedData['month'],
            'no_of_contact'     => $validatedData['no_of_contact'],
            'no_of_viewed'      => $validatedData['no_of_viewed'],
            'balance'           => $validatedData['balance'],
            'no_of_chats'       => $validatedData['no_of_chats'],
            'viewed_chats'      => $validatedData['viewed_chats'],
            'balance_chats'     => $validatedData['balance_chats'],
            'no_of_interests'   => $validatedData['no_of_interests'],
            'viewed_interests'  => $validatedData['viewed_interests'],
            'balance_interests' => $validatedData['balance_interests'],
            'recharge_date'     => $validatedData['recharge_date'],
            'expiry_date'       => (!empty($validatedData['expiry_date']) && ((int)($validatedData['month'] ?? 0) > 0)) ? $validatedData['expiry_date'] : null,
            'status'            => 'paid',
            'updated_at'        => now(),
        ];

        $existing = DB::table('receipts')
            ->where('order_id', $validatedData['order_id'])
            ->first();

        if ($existing) {
            DB::table('receipts')
                ->where('order_id', $validatedData['order_id'])
                ->update($payDetails);

            return response()->json([
                'success' => "Payment Successful",
                'payment_details' => array_merge([
                    'user_id' => $validatedData['user_id'],
                    'order_id' => $validatedData['order_id'],
                ], $payDetails)
            ]);
        } else {
            return response()->json([
                'error' => "No matching receipt found",
                'status' => 'Not Found'
            ], 404);
        }
    }

}
