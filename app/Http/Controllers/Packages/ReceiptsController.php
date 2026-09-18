<?php

namespace App\Http\Controllers\Packages;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReceiptsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $amount = $request->query('amount');
        $userId = $request->query('user_id');
        $package = $request->query('package');
        $month = $request->query('month');
        $no_of_contact = $request->query('no_of_contact');
        return view('web.receipts', compact('amount', 'userId', 'package', 'month', 'no_of_contact'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $userId = $request->input('user_id');
        $name = $request->input('name');
        $amount = $request->input('amount');
        $package = $request->input('package');
        $month = (int) $request->input('month');
        $no_of_contact = $request->input('no_of_contact');
        $no_of_chats = $request->input('no_of_chats');
        $no_of_interests = $request->input('no_of_interests');

        $transactionId = Str::uuid()->toString();

        $now = Carbon::now();
        $rechargeDate = $now->toDateTimeString();
        $expiryDate = $month > 0 ? $now->copy()->addMonths((int)$month)->toDateTimeString() : null;

        // Update or insert the record
        DB::table('receipts')->updateOrInsert(
            ['user_id' => $userId], // Search condition
            [
                'name' => $name,
                'amount' => $amount,
                'package' => $package,
                'month' => $month,
                'no_of_contact' => $no_of_contact,
                'balance' => $no_of_contact,
                'no_of_viewed' => 0,
                'no_of_chats' => $no_of_chats,
                'viewed_chats' => 0,
                'balance_chats' => $no_of_chats,
                'no_of_interests' => $no_of_interests,
                'viewed_interests' => 0,
                'balance_interests' => $no_of_interests,
                'status' => 'paid',
                'razorpay_order_id' => $transactionId,
                'recharge_date' => $rechargeDate,
                'expiry_date' => $expiryDate,
            ]
        );

        return redirect('dashboard')->with('success', 'Receipt submitted successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
