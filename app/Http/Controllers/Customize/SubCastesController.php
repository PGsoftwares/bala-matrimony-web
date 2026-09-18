<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubCastesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $castes = DB::table('castes')->orderBy('name', 'asc')->get();
        $subCastes = DB::table('sub_castes')
            ->leftJoin('castes', 'sub_castes.caste_id', '=', 'castes.id')
            ->select('sub_castes.*', 'castes.name as caste_name')
            ->orderBy('sub_castes.name', 'asc')
            ->paginate(10);
        return view('admin.customize.sub-castes', ['castes' => $castes, 'subCastes' => $subCastes]);
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
    public function store(Request $request):RedirectResponse
    {
        $insert = [
            'caste_id' => $request->input('caste_id'),
            'name' => $request->input('name'),
        ];

        $subCaste = new Customize();
        $subCastes = $subCaste->storeSubCastes($insert);
        return redirect('admin/sub-castes')->with('success', 'sub-caste added successfully');
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
    public function update(Request $request, string $id):RedirectResponse
    {
        $update = [
            'caste_id' => $request->input('caste_id'),
            'name' => $request->input('name'),
        ];
        DB::table('sub_castes')->where('id', $id)->update($update);
        return redirect('admin/sub-castes')->with('success', 'sub-caste updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):RedirectResponse
    {
        $data['delete'] = DB::table('sub_castes')->where('id', $id)->delete();
        return redirect('admin/sub-castes')->with('success', 'sub-castes deleted successfully');
    }
}
