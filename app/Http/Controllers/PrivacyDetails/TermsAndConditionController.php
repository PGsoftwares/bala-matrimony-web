<?php

namespace App\Http\Controllers\PrivacyDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TermsAndConditionController extends Controller
{
    public function termsAndCondition(): View
    {
        $termsAndConditions = DB::table('terms_conditions')->first();
        return view('admin.privacyDetails.terms-and-conditions', compact('termsAndConditions'));
    }

    public function termsAndConditionStore(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => 'required',
        ]);

        $data = [
            'content' => $request->input('content'),
        ];

        // Check if there is an existing record to update
        $existingRecord = DB::table('terms_conditions')->first();

        if ($existingRecord) {
            // Update existing record
            DB::table('terms_conditions')
                ->where('id', $existingRecord->id)
                ->update($data);
        } else {
            // Insert new record
            DB::table('terms_conditions')->insert($data);
        }

        return redirect()->back()->with('success', 'Terms and Conditions saved successfully!');
    }


    public function termsAndConditionImageStore(Request $request): JsonResponse
    {
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('TermsAndCondition'), $imageName);

            // Correct the URL generation
            $url = asset('TermsAndCondition/' . $imageName);

            return response()->json([
                'uploaded' => 1,
                'imageName' => $imageName,
                'url' => $url
            ]);
        }

        return response()->json(['uploaded' => 0, 'error' => ['message' => 'No file uploaded.']]);
    }
}
