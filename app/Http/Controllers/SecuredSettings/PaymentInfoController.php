<?php

namespace App\Http\Controllers\SecuredSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $paymentInfos = DB::table('payment_settings')->get();
        return view('admin.securedSettings.payment_info', compact('paymentInfos'));
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

        if ($request->hasFile('qr_image')) {
            $image = $request->file('qr_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('paymentImage'), $imageName);
        }

        $paymentInfos = [
            'qr_image' => $imageName,
            'acc_name' => $request->input('acc_name'),
            'acc_number' => $request->input('acc_number'),
            'ifsc_code' => $request->input('ifsc_code'),
            'bank' => $request->input('bank'),
            'pay_number' => $request->input('pay_number'),
            'upi_id' => $request->input('upi_id'),
        ];

        DB::table('payment_settings')->insert($paymentInfos);
        return redirect('admin/payment_info')->with('success', 'Payment Info Added Successfully');
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
    public function update(Request $request, string $id): RedirectResponse
    {
        $paymentInfos = DB::table('payment_settings')->where('id', $id)->first();
        if ($request->hasFile('qr_image')) {
            $image = $request->file('qr_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('paymentImage'), $imageName);

            // Delete old image if exists
            if ($paymentInfos && file_exists(public_path('paymentImage/' . $paymentInfos->qr_image))) {
                unlink(public_path('paymentImage/' . $paymentInfos->qr_image));
            }
        } else {
            $imageName = $paymentInfos->qr_image;
        }

        $update = [
            'qr_image' => $imageName,
            'acc_name' => $request->input('acc_name'),
            'acc_number' => $request->input('acc_number'),
            'ifsc_code' => $request->input('ifsc_code'),
            'bank' => $request->input('bank'),
            'pay_number' => $request->input('pay_number'),
            'upi_id' => $request->input('upi_id'),
        ];

        DB::table('payment_settings')->where('id', $id)->update($update);
        return redirect('admin/payment_info')->with('success', 'Payment Info Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        DB::table('payment_settings')->where('id', $id)->delete();
        return redirect('admin/payment_info')->with('success', 'Payment Info Deleted Successfully');
    }
}
