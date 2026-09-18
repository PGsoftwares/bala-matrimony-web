<?php

namespace App\Http\Controllers\SecuredSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ContactInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $contactInfos = DB::table('contact_info')->get();
        return view('admin.securedSettings.contact_info', compact('contactInfos'));
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
        $contactInfos = [
            'email' => $request->input('email'),
            'mobile_1' => $request->input('mobile_1'),
            'mobile_2' => $request->input('mobile_2'),
            'whatsapp' => $request->input('whatsapp'),
            'address' => $request->input('address'),
        ];

        DB::table('contact_info')->insert($contactInfos);
        return redirect('admin/contact_info')->with('success', 'Contact Info Added Successfully');
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
        $updateContactInfo = [
            'email' => $request->input('email'),
            'mobile_1' => $request->input('mobile_1'),
            'mobile_2' => $request->input('mobile_2'),
            'whatsapp' => $request->input('whatsapp'),
            'address' => $request->input('address'),
        ];
        DB::table('contact_info')->where('id', $id)->update($updateContactInfo);
        return redirect('admin/contact_info')->with('success', 'Contact Info Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        DB::table('contact_info')->where('id', $id)->delete();
        return redirect('admin/contact_info')->with('success', 'Contact Info Deleted Successfully');
    }
}
