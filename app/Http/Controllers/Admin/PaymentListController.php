<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentListController extends Controller
{
    /**
     * Display the payment list with statistics and filters.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $paymentMethod = $request->get('payment_method');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Base query for payments
        $query = DB::table('receipts')
            ->leftJoin('users', 'receipts.user_id', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'receipts.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.mobile as user_mobile',
                'users.status as user_status',
                'user_details.gender as user_gender'
            )
            ->orderBy('receipts.id', 'desc');

        // Apply Status Filter
        if (!empty($status) && $status !== 'all') {
            $query->where('receipts.status', $status);
        }

        // Apply Payment Method Filter
        if (!empty($paymentMethod) && $paymentMethod !== 'all') {
            $query->where('receipts.payment_method', $paymentMethod);
        }

        // Apply Date Range Filter
        if (!empty($dateFrom)) {
            $query->whereDate('receipts.recharge_date', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('receipts.recharge_date', '<=', $dateTo);
        }

        // Apply Search Filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('receipts.order_id', 'LIKE', "%{$search}%")
                  ->orWhere('receipts.package', 'LIKE', "%{$search}%")
                  ->orWhere('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('users.email', 'LIKE', "%{$search}%")
                  ->orWhere('users.mobile', 'LIKE', "%{$search}%");
            });
        }

        $receipts = $query->get();

        // Calculate KPI Statistics
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateTimeString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateTimeString();
        $todayStart = $now->copy()->startOfDay()->toDateTimeString();
        $todayEnd = $now->copy()->endOfDay()->toDateTimeString();

        $stats = [
            'total_revenue' => DB::table('receipts')->where('status', 'paid')->sum('amount'),
            'month_revenue' => DB::table('receipts')
                ->where('status', 'paid')
                ->whereBetween('recharge_date', [$startOfMonth, $endOfMonth])
                ->sum('amount'),
            'today_revenue' => DB::table('receipts')
                ->where('status', 'paid')
                ->whereBetween('recharge_date', [$todayStart, $todayEnd])
                ->sum('amount'),
            'total_transactions' => DB::table('receipts')->count(),
            'paid_count' => DB::table('receipts')->where('status', 'paid')->count(),
            'pending_count' => DB::table('receipts')->where('status', 'pending')->count(),
            'failed_count' => DB::table('receipts')->where('status', 'failed')->count(),
            'closed_count' => DB::table('receipts')->where('status', 'closed')->count(),
        ];

        $filters = [
            'status' => $status,
            'payment_method' => $paymentMethod,
            'search' => $search,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        return view('admin.payments.index', compact('receipts', 'stats', 'filters'));
    }

    /**
     * Get specific payment details as JSON for modal / preview.
     */
    public function show(string $id): JsonResponse
    {
        $receipt = DB::table('receipts')
            ->leftJoin('users', 'receipts.user_id', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'receipts.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.mobile as user_mobile',
                'user_details.gender as user_gender'
            )
            ->where('receipts.id', $id)
            ->first();

        if (!$receipt) {
            return response()->json(['status' => false, 'message' => 'Payment record not found.'], 404);
        }

        return response()->json([
            'status' => true,
            'receipt' => $receipt,
        ]);
    }
}
