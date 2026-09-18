<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OccupationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $occupation_types = DB::table('occupation_type')->orderBy('name', 'asc')->get();
        $occupations = DB::table('occupation')
            ->leftJoin('occupation_type', 'occupation.occupation_type_id', '=', 'occupation_type.id')
            ->select('occupation.*', 'occupation_type.name as occupation_type_name')
            ->orderBy('occupation.name', 'asc')
            ->paginate(10);
        return view('admin.customize.occupation', compact('occupation_types', 'occupations'));
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
            'occupation_type_id' => $request->input('occupation_type_id'),
            'name' => $request->input('name'),
        ];

        $occupations = new Customize();
        $occupation = $occupations->storeOccupation($insert);
        return redirect('admin/occupation')->with('success', 'Occupation added successfully');
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
            'occupation_type_id' => $request->input('occupation_type_id'),
            'name' => $request->input('name'),
        ];
        DB::table('occupation')->where('id', $id)->update($update);
        return redirect('admin/occupation')->with('success', 'Occupation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('occupation')->where('id', $id)->delete();
        return redirect('admin/occupation')->with('success', 'Occupation deleted successfully');
    }
}
