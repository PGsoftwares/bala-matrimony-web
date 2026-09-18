<?php

namespace App\Http\Controllers\SecuredSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PrintInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $printInfos = DB::table('print_info')->get();
        return view('admin.securedSettings.print_info', compact('printInfos'));
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
        try {
            $request->validate([
                'logo' => 'required|image|max:1024',
                'contact' => 'required',
                'address' => 'required'
            ]);

            $existingInfo = DB::table('print_info')->first();
            if ($existingInfo) {
                return redirect()->back()->with('error', 'Print Info already exists. Please update the existing one.');
            }

            $imageName = '';
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $imageName = date('dmYHis') . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('printLogo'), $imageName);
            }

            $insert = [
                'contact' => $request->input('contact'),
                'address' => $request->input('address'),
                'logo' => $imageName,
            ];

            DB::table('print_info')->insert($insert);
            return redirect('admin/print_info')->with('success', 'Print Info Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
        try {
            $request->validate([
                'logo' => 'nullable|image|max:1024',
            ]);

            $printInfo = DB::table('print_info')->where('id', $id)->first();
            if (!$printInfo) {
                throw new \Exception('Print Info not found');
            }

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $imageName = date('dmYHis') . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('printLogo'), $imageName);

                if (file_exists(public_path('printLogo/' . $printInfo->logo))) {
                    unlink(public_path('printLogo/' . $printInfo->logo));
                }
            } else {
                $imageName = $printInfo->logo;
            }

            $update = [
                'contact' => $request->input('contact'),
                'address' => $request->input('address'),
                'logo' => $imageName,
            ];

            DB::table('print_info')->where('id', $id)->update($update);
            return redirect('admin/print_info')->with('success', 'Print Info Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        DB::table('print_info')->where('id', $id)->delete();
        return redirect('admin/print_info')->with('success', 'Print Info Deleted Successfully');
    }
}
