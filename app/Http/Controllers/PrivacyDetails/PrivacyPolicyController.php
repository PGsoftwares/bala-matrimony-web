<?php

namespace App\Http\Controllers\PrivacyDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
    public function privacyPolicy(): View
    {
        $privacyPolicy = DB::table('privacy_policy')->first();
        return view('admin.privacyDetails.privacy-policy', compact('privacyPolicy'));
    }

    public function privacyPolicyStore(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => 'required',
        ]);

        $data = [
            'content' => $request->input('content'),
        ];

        // Check if a record exists
        $existingRecord = DB::table('privacy_policy')->first();

        if ($existingRecord) {
            // Update the existing record
            DB::table('privacy_policy')->update($data);
        } else {
            // Insert a new record
            DB::table('privacy_policy')->insert($data);
        }

        return redirect()->back()->with('success', 'Privacy policy saved successfully!');
    }


    public function privacyPolicyImageStore(Request $request): JsonResponse
    {
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('PrivacyPolicy'), $imageName);

            // Correct the URL generation
            $url = asset('PrivacyPolicy/' . $imageName);

            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json(['uploaded' => 0, 'error' => ['message' => 'No file uploaded.']]);
    }
}
