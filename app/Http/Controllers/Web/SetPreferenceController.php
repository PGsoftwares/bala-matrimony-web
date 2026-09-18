<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\Helpers\DropdownController;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SetPreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $userPackage = DataController::getUserPackageDetails($userId);
        $isPackageValid = $userPackage['is_active'];
        $userPackageValue = $userPackage['package'];

        $oppositeGender = DataController::getOppositeGender($userAndUserDetails->gender);

        $preference = DB::table('set_preferences')
            ->where('user_id', $userId)
            ->select('set_preferences.*')
            ->first();

        $locationData = DropdownController::getCombinedLocationData($preference);
        $dropdownData = DropdownController::getDropdownData(
            $preference?->qualification, $preference?->education,
            $preference?->occupation_type, $preference?->occupation,
            $preference?->caste, $preference?->sub_caste
        );

        $metaTags = DataSharedController::MetaData('set-preference');
        $db = DataSharedController::getDatabases();

        return view('web.set-preference', array_merge(
            [
                'userAndUserDetails' => $userAndUserDetails, 'metaTags' => $metaTags, 'preference' => $preference,
                'isPackageValid' => $isPackageValid, 'db' => $db,
                'locationData' => $locationData, 'dropdownData' => $dropdownData
            ]
        ));
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
        $userId = Auth::id() ?? $request->input('userId');

        /*  list every field that can come in as an array  */
        $multiSelectFields = [
            'country', 'state', 'city',
            'mother_tongue', 'marital_status', 'skin_tone', 'body_type',
            'drinking_habit', 'smoking_habit', 'eating_habit', 'physical_status',
            'religion', 'caste', 'sub_caste', 'qualification', 'education',
            'occupation_type', 'occupation', 'employed_in',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham',
            'ethnicity', 'nationality', 'work_country', 'visa_status'
        ];

        /* start the data array with scalar / range fields  */
        $preferenceData = [
            'user_id'            => $userId,
            'min_age'            => $request->input('min_age'),
            'max_age'            => $request->input('max_age'),
            'height_from'        => $request->input('height_from'),
            'height_to'          => $request->input('height_to'),
            'monthly_income_from'=> $request->input('monthly_income_from'),
            'monthly_income_to'  => $request->input('monthly_income_to'),
        ];

        foreach ($multiSelectFields as $field) {
            $value = $request->input($field);
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $preferenceData[$field] = $value;
        }

        $exists = DB::table('set_preferences')->where('user_id', $userId)->exists();
        if ($exists) {
            DB::table('set_preferences')
                ->where('user_id', $userId)
                ->update($preferenceData);
            $message = 'Preferences updated successfully.';
        } else {
            DB::table('set_preferences')->insert($preferenceData);
            $message = 'Preferences added successfully.';
        }
        return redirect()->back()->with('success', $message);
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
