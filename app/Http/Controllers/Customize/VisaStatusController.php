<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VisaStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $visaStatus = DB::table('visa_status')->orderBy('name', 'asc')->paginate(10);
        return view('admin.customize.visa-status', compact('visaStatus'));
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
                'visa_status' => 'required|string',
            ]);
            DB::table('visa_status')->insert([
                'name' => $request->input('visa_status'),
            ]);
            return back()->with('success', 'Visa Added Successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong!');
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
                'visa_status' => 'required|string',
            ]);
            DB::table('visa_status')->where('id', $id)->update([
                'name' => $request->input('visa_status'),
            ]);
            return back()->with('success', 'Visa Updated Successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            DB::table('visa_status')->where('id', $id)->delete();
            return back()->with('success', 'Visa Deleted Successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong!');
        }
    }
}
