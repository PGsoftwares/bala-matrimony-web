<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\WebController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PlanController extends Controller
{

    protected  $webController;

    public function __construct(WebController $webController)
    {
        $this->webController = $webController;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $userPackage = DataController::getUserPackageDetails($userId);
        $isPackageValid = $userPackage['is_active'];
        $receipt = $userPackage['receipt'];

        $metaTags = DataSharedController::MetaData('plan');
        $db = DataSharedController::getDatabases();

        return view('web.plan', compact('userAndUserDetails','userPackage', 'isPackageValid', 'receipt', 'metaTags', 'db'));
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
