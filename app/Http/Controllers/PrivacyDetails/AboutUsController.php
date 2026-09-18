<?php

namespace App\Http\Controllers\PrivacyDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AboutUsController extends Controller
{
    public function aboutUs(): View
    {
        $aboutUs = DB::table('about_us')->first();
        return view('admin.privacyDetails.about-us', compact('aboutUs'));
    }

    public function aboutUsStore(Request $request): RedirectResponse
    {
        // Handle form submission
        $request->validate([
            'content' => 'required',
        ]);

        $data = [
            'content' => $request->input('content'),
        ];

        $existingRecord = DB::table('about_us')->first();

        if ($existingRecord) {
            DB::table('about_us')->update($data);
        } else {
            DB::table('about_us')->insert($data);
        }

        return redirect()->back()->with('success', 'About us saved successfully!');
    }

    public function aboutUsImageStore(Request $request): JsonResponse
    {
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('AboutUs'), $imageName);

            // Correct the URL generation
            $url = asset('AboutUs/' . $imageName);

            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json(['uploaded' => 0, 'error' => ['message' => 'No file uploaded.']]);
    }
}
