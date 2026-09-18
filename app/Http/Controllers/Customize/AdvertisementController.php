<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $advertisements = DB::table('advertisement')->paginate(10);
        return view('admin.customize.advertisement', compact('advertisements'));
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
        $data = [];

        for ($i = 1; $i <= 4; $i++) {
            $inputName = 'add_' . $i;

            if ($request->hasFile($inputName)) {
                $image = $request->file($inputName);
                $imageName = date('dmYHis') . "_$i." . $image->getClientOriginalExtension();
                $image->move(public_path('Advertisement'), $imageName);
                $data[$inputName] = $imageName;
            } else {
                $data[$inputName] = '';
            }
        }

        DB::table('advertisement')->insert($data);
        return redirect('admin/advertisement')->with('success', 'Advertisement added successfully');
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
        $advertisement = DB::table('advertisement')->where('id', $id)->first();

        if (!$advertisement) {
            return redirect('admin/advertisement')->with('success', 'Advertisement not found');
        }

        $data = [];

        for ($i = 1; $i <= 4; $i++) {
            $field = "add_$i";
            $currentImage = $advertisement->$field;

            if ($request->hasFile($field)) {
                $oldPath = public_path("Advertisement/{$currentImage}");
                if ($currentImage && file_exists($oldPath)) {
                    unlink($oldPath);
                }
                // Store new image
                $image = $request->file($field);
                $imageName = date('dmYHis') . "_$i." . $image->getClientOriginalExtension();
                $image->move(public_path('Advertisement'), $imageName);

                $data[$field] = $imageName;
            } else {
                $data[$field] = $currentImage;
            }
        }

        DB::table('advertisement')->where('id', $id)->update($data);
        return redirect('admin/advertisement')->with('success', 'Advertisement updated successfully');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $advertisement = DB::table('advertisement')->where('id', $id)->first();

        if (!$advertisement) {
            return redirect('admin/advertisement')->with('success', 'Advertisement not found');
        }

        // Loop through all 4 image fields and delete them if they exist
        for ($i = 1; $i <= 4; $i++) {
            $field = "add_$i";
            $imagePath = public_path("Advertisement/" . $advertisement->$field);

            if (!empty($advertisement->$field) && file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        DB::table('advertisement')->where('id', $id)->delete();
        return redirect('admin/advertisement')->with('success', 'Advertisement deleted successfully');
    }


}
