<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TestingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Retrieve list of users excluding admins
        $profiles = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('receipts', 'users.id', '=', 'receipts.user_id')
            ->where('users.role', '!=', 'admin')
            ->select('users.*', 'user_details.*','receipts.package as package')
            ->get();

        return view('admin.register-test', compact('profiles'));
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


    public function searchMembers(Request $request): View
    {

        // Fetch options for filter dropdowns
        $databaseData = [
            'languages' => DB::table('languages')->get(),
            'maritalStatues' => DB::table('marital_status')->get(),
            'skinTones' => DB::table('skin_tone')->get(),
            'heights' => DB::table('height')->get(),
            'bodyTypes' => DB::table('body_type')->get(),
            'drinkingHabits' => DB::table('drinking_habits')->get(),
            'smokingHabits' => DB::table('smoking_habits')->get(),
            'castes' => DB::table('castes')->get(),
            'subCastes' => DB::table('sub_castes')->get(),
            'religions' => DB::table('religion')->get(),
            'educationLevels' => DB::table('education_level')->pluck('name', 'id'),
            'educations' => DB::table('education')->get(),
            'countries' => DB::table('countries')->get(),
            'states' => DB::table('states')->get(),
            'cities' => DB::table('cities')->get(),
            'rashies' => DB::table('rashi')->get(),
            'nakshatras' => DB::table('stars')->get(),
            'lagnams' => DB::table('lagnam')->get(),
            'padams' => DB::table('padam')->get(),
            'kulams' => DB::table('kulam')->get(),
            'gothrams' => DB::table('gothram')->get(),
            'doshams' => DB::table('dosham')->get(),
            'packageSettings' => DB::table('package_settings')->get(),
            'userPermissions' => DB::table('user_permission')->get()
        ];

        // Fetch user details
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->get();

        // Base query to fetch profiles
        $profilesQuery = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('education_level', 'education_level.id', '=', 'user_details.qualification')
            ->leftJoin('occupation_type', 'occupation_type.id', '=', 'user_details.occupation_type')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->orderBy('user_details.created_at', 'desc')
            ->select('user_details.*', 'users.*', 'education_level.name as qualification',
                'occupation_type.name as occupation_type', 'settings.profile_picture_visibility');

        // Store applied filters for display
        $appliedFilters = [];

        // Apply filters based on request parameters
        $filters = [
            'mother_tongue', 'age', 'marital_status', 'skin_tone', 'height', 'body_type',
            'drinking_habit', 'smoking_habit', 'religion', 'caste', 'sub_caste', 'qualification',
            'education', 'occupation_type', 'occupation', 'country', 'state', 'city',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham'
        ];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                if ($filter === 'age') {
                    $ageRange = explode(' to ', $request->input('age'));
                    $profilesQuery->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE())'), [$ageRange[0], $ageRange[1]]);
                } else {
                    $profilesQuery->where('user_details.' . $filter, $request->input($filter));
                }
                // Add filter to applied filters array
                $appliedFilters[$filter] = $request->input($filter);
            }
        }

        // Filter by date range (from_date and to_date)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $profilesQuery->whereBetween('users.created_at', [$request->input('from_date'), $request->input('to_date')]);
            $appliedFilters['from_date'] = $request->input('from_date');
            $appliedFilters['to_date'] = $request->input('to_date');
        }

        // Filter by month and year in "September 2024" format
        if ($request->filled('month') && $request->filled('year')) {
            $profilesQuery->where(DB::raw("DATE_FORMAT(users.created_at, '%M %Y')"), "{$request->input('month')} {$request->input('year')}");
            $appliedFilters['month'] = $request->input('month');
            $appliedFilters['year'] = $request->input('year');
        }


        // Fetch profiles with pagination
        $profiles = $profilesQuery->get()->each(function ($profile) {
            $profile->age = Carbon::parse($profile->dob)->age;
        });

        // Profile count
        $profilesCount = $profiles->count();

        // If no profiles found
        if ($profilesCount === 0) {
            return view('admin.register-test', array_merge([
                'userAndUserDetails' => $userAndUserDetails,
                'profiles' => $profiles,
                'profilesCount' => $profilesCount,
                'appliedFilters' => $appliedFilters,
                'message' => 'No profiles found for the selected criteria.'
            ], $databaseData));
        }

        return view('admin.register-test', array_merge([
            'userAndUserDetails' => $userAndUserDetails,
            'profiles' => $profiles,
            'profilesCount' => $profilesCount,
            'appliedFilters' => $appliedFilters,
        ], $databaseData));
    }

}
