<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MobileAdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $mobileAdvertisements = DB::table('mobile_adds')->paginate(10);
        return view('admin.customize.mobile_advertisement', compact('mobileAdvertisements'));
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
        $image = '';

        // Handle the first image
        if ($request->hasFile('image')) {
            $imageName = $request->file('image');
            $image = 'MOBILE_' . date('Ymd_His') . '.' . $imageName->getClientOriginalExtension();
            $imageName->move(public_path('Advertisement'), $image);
        }

        $data = [
            'image' => $image,
        ];

        DB::table('mobile_adds')->insert($data);

        return redirect('admin/mobile_advertisement')->with('success', 'Advertisement added successfully');
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
        $mobileAdvertisement = DB::table('mobile_adds')->where('id', $id)->first();

        if (!$mobileAdvertisement) {
            return redirect('admin/mobile_advertisement')->with('success', 'Advertisement not found');
        }

        $imageName = $mobileAdvertisement->image;

        // Update the first image if a new one is uploaded
        if ($request->hasFile('image')) {
            if ($mobileAdvertisement->image && file_exists(public_path('Advertisement/' . $mobileAdvertisement->image))) {
                unlink(public_path('Advertisement/' . $mobileAdvertisement->image));
            }

            $image_1 = $request->file('image');
            $imageName = 'MOBILE_' . date('Ymd_His') . '.' . $image_1->getClientOriginalExtension();
            $image_1->move(public_path('Advertisement'), $imageName);
        }

        // Update the advertisement in the database
        DB::table('mobile_adds')->where('id', $id)->update([
            'image' => $imageName,
        ]);

        return redirect('admin/mobile_advertisement')->with('success', 'Advertisement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $mobileAdvertisement = DB::table('mobile_adds')->where('id', $id)->first();

        if (!$mobileAdvertisement) {
            return redirect('admin/mobile_advertisement')->with('success', 'Advertisement not found');
        }

        if ($mobileAdvertisement->image && file_exists(public_path('Advertisement/' . $mobileAdvertisement->image))) {
            unlink(public_path('Advertisement/' . $mobileAdvertisement->image));
        }

        // Delete the advertisement from the database
        DB::table('mobile_adds')->where('id', $id)->delete();

        return redirect('admin/mobile_advertisement')->with('success', 'Advertisement deleted successfully');
    }

    /**
     * Mobile App Slider image Functions
     */
    public function getAppSlider(): View
    {
        $appSliders = DB::table('slider_images')->paginate(10);
        return view('admin.customize.app-slider', compact('appSliders'));
    }

    public function appSliderStore(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required'
        ]);

        try {
            if ($request->hasFile('image')) {
                $image       = $request->file('image');
                $imageName   = date('dmYHis') . '.' . $image->getClientOriginalExtension();
                $destination = public_path('web/SliderImage');
                $image->move($destination, $imageName);

                DB::table('slider_images')->insert([
                    'image'      => $imageName,
                ]);

                return back()->with('success', 'Image uploaded successfully.');
            } else {
                return back()->with('error', 'No image was uploaded.');
            }
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while uploading the image.');
        }
    }

    public function appSliderUpdate(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'image' => 'nullable'
        ]);

        try {
            $slider = DB::table('slider_images')->where('id', $id)->first();
            if (!$slider) {
                return back()->with('error', 'Slider not found.');
            }
            $imageName = $slider->image;

            if ($request->hasFile('image')) {
                $image     = $request->file('image');
                $newName   = date('dmYHis') . '.' . $image->getClientOriginalExtension();
                $path      = public_path('web/SliderImage');
                $image->move($path, $newName);

                // Delete old image
                $oldImagePath = public_path('web/SliderImage/' . $slider->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $imageName = $newName;
            }
            DB::table('slider_images')->where('id', $id)->update([
                'image' => $imageName,
            ]);

            return back()->with('success', 'Slider updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong! while updating the image. ');
        }
    }

    public function appSliderDelete($id): RedirectResponse
    {
        try {
            $slider = DB::table('slider_images')->where('id', $id)->first();
            if (!$slider) {
                return back()->with('error', 'Slider not found.');
            }
            // Delete the image file
            $imagePath = public_path('web/SliderImage/' . $slider->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            DB::table('slider_images')->where('id', $id)->delete();
            return back()->with('success', 'Slider deleted successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong! while deleting the image. ');
        }
    }
}
