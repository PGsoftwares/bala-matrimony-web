<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $languages = DB::table('languages')->orderBy('language', 'asc')->paginate(10);
        return view('admin.customize.languages', ['languages' => $languages]);
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
            'language' => $request->input('language'),
        ];

        $language = new Customize();
        $languages = $language->storeLanguages($insert);
        return redirect('admin/languages')->with('success', 'Mother tongue added successfully');
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
            'language' => $request->input('language'),
        ];
        DB::table('languages')->where('id', $id)->update($update);
        return redirect('admin/languages')->with('success', 'Mother tongue updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):RedirectResponse
    {
        $data['delete'] = DB::table('languages')->where('id', $id)->delete();
        return redirect('admin/languages')->with('success', 'Mother tongue deleted successfully');
    }
}
