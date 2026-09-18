<?php

namespace App\Http\Controllers\PrivacyDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RefundPolicyController extends Controller
{
    public function refundPolicy(): View
    {
        $refundPolicy = DB::table('refund_policy')->first();
        return view('admin.privacyDetails.refund-policy', compact('refundPolicy'));
    }

    public function refundPolicyStore(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => 'required',
        ]);

        $data = [
            'content' => $request->input('content'),
        ];

        $existingRecord = DB::table('refund_policy')->first();

        if ($existingRecord) {
            DB::table('refund_policy')->update($data);
        } else {
            DB::table('refund_policy')->insert($data);
        }

        return redirect()->back()->with('success', 'Refund policy saved successfully!');
    }

}
