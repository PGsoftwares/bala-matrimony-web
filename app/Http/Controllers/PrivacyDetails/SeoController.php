<?php

namespace App\Http\Controllers\PrivacyDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $metaTags = DB::table('meta_tags')->paginate(10);
        return view('admin.privacyDetails.seo', compact('metaTags'));
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
        $insertMetaTag = DB::table('meta_tags')->insert([
           'page' => $request->input('page'),
           'title' => $request->input('title'),
           'description' => $request->input('description'),
           'keywords' => $request->input('keywords'),
        ]);

        return redirect('admin/seo')->with('success', 'Meta Tag added successfully');
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
        $updateMetaTag = DB::table('meta_tags')->where('id', $id)->update([
            'page' => $request->input('page'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'keywords' => $request->input('keywords'),
        ]);
        return redirect('admin/seo')->with('success', 'Meta Tag updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $deleteMetaTag = DB::table('meta_tags')->where('id', $id)->delete();
        return redirect('admin/seo')->with('success', 'Meta Tag deleted successfully');
    }
}
