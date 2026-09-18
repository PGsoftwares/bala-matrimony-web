<?php

namespace App\Http\Controllers\Customize;

use App\Http\Controllers\Controller;
use App\Models\Customize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TableApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Fetch all table names
        $tables = DB::select('SHOW TABLES');
        $tableNames = array_map('current', $tables);

        $tableApprovals = DB::table('table_approvals')->paginate(10);
        return view('admin.customize.table-approval', compact('tableNames', 'tableApprovals'));
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
            'table_name' => $request->input('table_name'),
            'status' => $request->input('status'),
        ];

        $tableApproval = new Customize();
        $tableApprovals = $tableApproval->storeTableApproval($insert);
        return redirect('admin/table-approval')->with('success', 'Table Approval added successfully');
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
            'table_name' => $request->input('table_name'),
            'status' => $request->input('status'),
        ];
        DB::table('table_approvals')->where('id', $id)->update($update);
        return redirect('admin/table-approval')->with('success', 'Table Approval updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $data['delete'] = DB::table('table_approvals')->where('id', $id)->delete();
        return redirect('admin/table-approval')->with('success', 'Table Approval deleted successfully');
    }
}
