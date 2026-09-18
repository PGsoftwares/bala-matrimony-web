<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FamilyGodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $family_gods = DB::table('family_god')->orderBy('family_god', 'asc')->paginate(10);
        return view('admin.customize.family-god', ['family_gods'=> $family_gods]);
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
        $insert = [
            'family_god' => $request->input('family_god'),
            'admin_status' => $request->input('admin_status'),
        ];

        $family_god = new Customize();
        $family_gods = $family_god->storeFamilyGod($insert);
        return redirect('admin/family-god')->with('success', 'Family god added successfully');
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
        $update = [
            'family_god' => $request->input('family_god'),
            'admin_status' => $request->input('admin_status'),
        ];
        DB::table('family_god')->where('id', $id)->update($update);
        return redirect('admin/family-god')->with('success', 'Family god updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('family_god')->where('id', $id)->delete();
        return redirect('admin/family-god')->with('success', 'Family god deleted successfully');
    }
}
