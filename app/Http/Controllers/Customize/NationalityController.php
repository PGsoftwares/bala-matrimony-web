<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NationalityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $nationalities = DB::table('nationality')->orderBy('name', 'asc')->paginate(10);
        return view('admin.customize.nationality', compact('nationalities'));
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
                'nationality' => 'required|string',
            ]);
            DB::table('nationality')->insert([
                'name' => $request->input('nationality'),
            ]);
            return back()->with('success', 'Nationality Added Successfully');
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
                'nationality' => 'required|string',
            ]);
            DB::table('nationality')->where('id', $id)->update([
                'name' => $request->input('nationality'),
            ]);
            return back()->with('success', 'Nationality Updated Successfully');
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
            DB::table('nationality')->where('id', $id)->delete();
            return back()->with('success', 'Nationality Deleted Successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong!');
        }
    }
}
