<?php

namespace App\Http\Controllers\Packages;

use App\Http\Controllers\Controller;
use App\Models\Packages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $packages = DB::table('packages')->get();
        return view('admin.packages.packages', compact('packages'));
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
            'amount' => $request->input('amount'),
            'month' => $request->input('month'),
            'no_of_contact' => $request->input('no_of_contact'),
            'no_of_chats' => $request->input('no_of_chats'),
            'no_of_interests' => $request->input('no_of_interests'),
        ];

        $package = new Packages();
        $packages = $package->storePackages($insert);
        return redirect('admin/packages')->with('success', 'Package added successfully');
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
            'amount' => $request->input('amount'),
            'month' => $request->input('month'),
            'no_of_contact' => $request->input('no_of_contact'),
            'no_of_chats' => $request->input('no_of_chats'),
            'no_of_interests' => $request->input('no_of_interests'),
        ];

        DB::table('packages')->where('id', $id)->update($update);
        return redirect('admin/packages')->with('success', 'Package Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('packages')->where('id', $id)->delete();
        return redirect('admin/packages')->with('success', 'Packages deleted successfully');
    }


    public function packageReports(): View
    {
        $receipts = DB::table('receipts')
            ->leftJoin('users', 'receipts.user_id', '=', 'users.id')
            ->select('receipts.*', 'users.name')
            ->get();
        return view('admin.packages.package-reports', compact('receipts'));
    }
}
