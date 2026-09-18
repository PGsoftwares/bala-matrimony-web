<?php

namespace App\Http\Controllers\SecuredSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrackBrokerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $trackBrokerSettings = DB::table('track_broker')->get();
        return view('admin.securedSettings.track_broker', compact('trackBrokerSettings'));
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
        $request->validate([
            'no_of_login' => 'required',
//            'no_of_contact' => 'required',
//            'no_of_profile' => 'required',
        ]);

        DB::table('track_broker')->insert([
            'no_of_login' => $request->input('no_of_login'),
//            'no_of_contact' => $request->input('no_of_contact'),
//            'no_of_profile' => $request->input('no_of_profile'),
        ]);

        return redirect('admin/track_broker')->with('success', 'Track Broker setting applied successfully.');
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
        $request->validate([
            'no_of_login' => 'required',
//            'no_of_contact' => 'required',
//            'no_of_profile' => 'required',
        ]);
        DB::table('track_broker')->where('id', $id)->update([
            'no_of_login' => $request->input('no_of_login'),
//            'no_of_contact' => $request->input('no_of_contact'),
//            'no_of_profile' => $request->input('no_of_profile'),
        ]);
        return redirect('admin/track_broker')->with('success', 'Track Broker setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        DB::table('track_broker')->where('id', $id)->delete();
        return redirect('admin/track_broker')->with('success', 'Track Broker setting deleted successfully.');
    }
}
