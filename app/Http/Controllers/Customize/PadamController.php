<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PadamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $padams = DB::table('padam')->orderBy('name', 'asc')->paginate(10);
        return view('admin.customize.padam', ['padams' => $padams]);
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
            'name' => $request->input('name'),
        ];

        $padam = new Customize();
        $padams = $padam->storePadam($insert);
        return redirect('admin/padam')->with('success', 'Padam added successfully');
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
            'name' => $request->input('name'),
        ];
        DB::table('padam')->where('id', $id)->update($update);
        return redirect('admin/padam')->with('success', 'Padam updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('padam')->where('id', $id)->delete();
        return redirect('admin/padam')->with('success', 'Padam deleted successfully');
    }
}
