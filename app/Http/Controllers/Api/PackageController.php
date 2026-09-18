<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{

    public function getUserPackageData(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $userWithDetails = DataController::getUserDetails($userId);

        if (!$userWithDetails) {
            return response()->json([
                'status' => false,
                'message' => 'User details not found',
            ], 404);
        }

        // Check the user's package
        $package = DataController::getUserPackageDetails($userId);
        $receipt = $package['receipt'];

        if (!$receipt) {
            return response()->json([
                'status' => false,
                'message' => 'No package found for the user',
            ], 404);
        }

        // Return the response in JSON format
        return response()->json([
            'status' => true,
            'message' => 'User package details retrieved successfully',
            'package_name' => $receipt->package ?? '',
            'amount' => $receipt->amount ?? '',
            'month' => $receipt->month ?? 0,
            'valid_until' => (!empty($receipt->expiry_date) && ($receipt->month != 0)) ? \Carbon\Carbon::parse($receipt->expiry_date)->format('d F Y') : 'Without Expiry Date',
            'total_contact' => $receipt->no_of_contact ?? 0,
            'viewed_contact' => $receipt->no_of_viewed ?? 0,
            'available_contact' => $receipt->balance ?? 0,
            'total_chats' => $receipt->no_of_chats ?? 0,
            'viewed_chats' => $receipt->viewed_chats ?? 0,
            'available_chats' => $receipt->balance_chats ?? 0,
            'total_interests' => $receipt->no_of_interests ?? 0,
            'viewed_interests' => $receipt->viewed_interests ?? 0,
            'available_interests' => $receipt->balance_interests ?? 0,
        ]);
    }


}
