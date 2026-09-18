<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $educations = DB::table('education')
            ->leftJoin('education_level', 'education.education_level_id', '=', 'education_level.id')
            ->select('education.*', 'education_level.name as qualification_name')
            ->orderBy('education.name', 'asc')
            ->paginate(10);
        $education_levels = DB::table('education_level')->orderBy('name', 'asc')->get();
        return view('admin.customize.education', compact('educations', 'education_levels'));
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
            'education_level_id' => $request->input('education_level_id'),
            'name' => $request->input('name'),
        ];

        $educations = new Customize();
        $education = $educations->storeEducation($insert);
        return redirect('admin/education')->with('success', 'Education added successfully');
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
            'education_level_id' => $request->input('education_level_id'),
            'name' => $request->input('name'),
        ];
        DB::table('education')->where('id', $id)->update($update);
        return redirect('admin/education')->with('success', 'Education updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('education')->where('id', $id)->delete();
        return redirect('admin/education')->with('success', 'Education deleted successfully');
    }
}
