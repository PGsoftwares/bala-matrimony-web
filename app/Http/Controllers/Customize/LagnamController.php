<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LagnamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $lagnams = DB::table('lagnam')->orderBy('name', 'asc')->paginate(10);
        return view('admin.customize.lagnam', ['lagnams' => $lagnams]);
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

        $lagnam = new Customize();
        $lagnams = $lagnam->storeLagnam($insert);
        return redirect('admin/lagnam')->with('success', 'Lagnam added successfully');
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
        DB::table('lagnam')->where('id', $id)->update($update);
        return redirect('admin/lagnam')->with('success', 'Lagnam updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('lagnam')->where('id', $id)->delete();
        return redirect('admin/lagnam')->with('success', 'Lagnam deleted successfully');
    }
}
