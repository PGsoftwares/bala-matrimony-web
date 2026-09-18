<?php

namespace App\Http\Controllers\SecuredSettings;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $adminInfos = DB::table('users')->where('role',  '!=', 'standard')->get();
        return view('admin.securedSettings.admin_info', compact('adminInfos'));
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
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|unique:users',
                'mobile' => 'required|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $adminDataInputs = [
                'role' => $request->input('role'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => Hash::make($request->input('password')),
                'otp_verified_at' => Carbon::now(),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ];

            DB::table('users')->insert($adminDataInputs);
            return redirect('admin/admin_info')->with('success', 'Admin Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage() );
        }
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
        try {
            $request->validate([
                'password' => 'nullable|string|min:8',
            ]);
            $adminDataUpdates = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'updated_at' => Carbon::now(),
            ];
            if ($request->filled('password')) {
                $adminDataUpdates['password'] = Hash::make($request->input('password'));
            }
            DB::table('users')->where('id', $id)->update($adminDataUpdates);
            return redirect('admin/admin_info')->with('success', 'Admin Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : RedirectResponse
    {
        $adminInfo = DB::table('users')->where('id', $id)->first();
        DB::table('users')->where('id', $id)->delete();
        return redirect('admin/admin_info')->with('success', 'Admin Deleted Successfully');
    }
}
