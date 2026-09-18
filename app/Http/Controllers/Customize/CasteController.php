<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $castes = DB::table('castes')->orderBy('name', 'asc')->paginate(10);
        return view('admin.customize.castes', ['castes'=> $castes]);
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
            'name' => $request->input('name'),
        ];

        $caste = new Customize();
        $castes = $caste->storeCastes($insert);
        return redirect('admin/castes')->with('success', 'Caste added successfully');
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
            'name' => $request->input('name'),
        ];
        DB::table('castes')->where('id', $id)->update($update);
        return redirect('admin/castes')->with('success', 'Caste updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):RedirectResponse
    {
        $data['delete'] = DB::table('castes')->where('id', $id)->delete();
        return redirect('admin/castes')->with('success', 'Caste deleted successfully');
    }
}
