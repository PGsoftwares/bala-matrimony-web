<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataSharedController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $db = DataSharedController::getDatabases();

        $query = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '!=', 'admin')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.mobile',
                'users.role',
                'users.status',
                'user_details.*',
            );

        // Date validation
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $status = $request->input('status');

        if ($fromDate && $toDate && $fromDate > $toDate) {
            return redirect()->back()->with('error', 'From Date cannot be after To Date.');
        }

        // Apply filters
        $filters = [
            'gender' => 'user_details.gender',
            'mother_tongue' => 'user_details.mother_tongue',
            'marital_status' => 'user_details.marital_status',
            'skin_tone' => 'user_details.skin_tone',
            'height' => 'user_details.height',
            'body_type' => 'user_details.body_type',
            'drinking_habit' => 'user_details.drinking_habit',
            'smoking_habit' => 'user_details.smoking_habit',
            'religion' => 'user_details.religion',
            'caste' => 'user_details.caste',
            'qualification' => 'user_details.qualification',
            'education' => 'user_details.education',
            'occupation_type' => 'user_details.occupation_type',
            'occupation' => 'user_details.occupation',
            'rashi' => 'user_details.rashi',
            'nakshatra' => 'user_details.nakshatra',
            'lagnam' => 'user_details.lagnam',
            'dosham' => 'user_details.dosham',
            'state' => 'user_details.state',
            'taluk' => 'user_details.taluk',
            'district' => 'user_details.district',
            'today' => DB::raw('DATE(users.created_at)'),
            'specific_date' => DB::raw('DATE(users.created_at)'),
            'mobile' => 'users.mobile',
            'user_id' => 'users.id'
        ];

        foreach ($filters as $param => $column) {
            if ($request->filled($param)) {
                $values = $request->input($param);
                if (is_array($values)) {
                    $query->whereIn($column, $values);
                } else {
                    $query->where($column, $values);
                }
            }
        }

        // Apply created_at date filters
        if ($fromDate) {
            $query->whereDate('users.created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('users.created_at', '<=', $toDate);
        }

        // Apply search filter (ID, email, name, mobile)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('users.id', $search)->orWhere('users.mobile', $search);
                } elseif (filter_var($search, FILTER_VALIDATE_EMAIL)) {
                    $q->where('users.email', $search);
                } else {
                    $q->where('users.name', 'like', "%$search%")
                        ->orWhere('users.mobile', 'like', "%$search%");
                }
            });
        }


        if ($request->filled('min_age')) {
            $query->whereNotNull('user_details.dob')
                ->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) >= ?", [$request->input('min_age')]);
        }
        if ($request->filled('max_age')) {
            $query->whereNotNull('user_details.dob')
                ->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) <= ?", [$request->input('max_age')]);
        }
        if ($request->filled('status')) {
            $query->where("users.status", '=', $status);
        }

        // Paginate results
        $details['user-list'] = $query->orderByDesc('users.id')->paginate(16);
        return view('admin.user-list', compact('details','db'));
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
    public function destroy(string $id): RedirectResponse
    {
       DB::table('users')->where('id', $id)->delete();
       DB::table('user_details')->where('user_id', $id)->delete();
       DB::commit();
       return redirect()->to(url()->previous())->with('success', 'User has been deleted!');
    }


    public function ProfileAnalysis(Request $request): View
    {
        $db = DataSharedController::getDatabases();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select('users.*', 'user_details.*')
            ->first();

        // Base query to fetch profiles
        $profilesQuery = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings','user_details.user_id','=','settings.user_id')
            ->orderBy('user_details.created_at', 'desc')
            ->select('user_details.*', 'users.*', 'settings.profile_picture_visibility');

        // Store applied filters for display
        $appliedFilters = [];

        // Apply filters based on request parameters
        $filters = [
            'mother_tongue', 'age', 'gender', 'marital_status', 'skin_tone', 'height', 'body_type',
            'drinking_habit', 'smoking_habit', 'religion', 'caste', 'sub_caste', 'qualification',
            'education', 'occupation_type', 'occupation', 'country', 'state', 'city',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham',
            'ethnicity', 'nationality', 'work_country', 'visa_status',
        ];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                if ($filter === 'age') {
                    $ageRange = explode(' to ', $request->input('age'));
                    $profilesQuery->whereNotNull('user_details.dob')
                        ->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) BETWEEN ? AND ?", [$ageRange[0], $ageRange[1]]);
                } else {
                    $profilesQuery->where('user_details.' . $filter, $request->input($filter));
                }
                // Add a filter to an applied filters array
                $appliedFilters[$filter] = $request->input($filter);
            }
        }

        $profiles = $profilesQuery->paginate(16);

        // Add ages in a separate loop if needed
        foreach ($profiles as $profile) {
            $profile->age = Carbon::parse($profile->dob)->age;
        }
        $profilesCount = $profiles->count();

        if ($profilesCount === 0) {
            return view('admin.profile-analysis', array_merge([
                'userAndUserDetails' => $userAndUserDetails,
                'profiles' => $profiles,
                'profilesCount' => $profilesCount,
                'appliedFilters' => $appliedFilters,
                'db' => $db,
                'message' => 'No profiles found for the selected criteria.',
            ]));
        }

        return view('admin.profile-analysis', array_merge([
            'userAndUserDetails' => $userAndUserDetails,
            'profiles' => $profiles,
            'profilesCount' => $profilesCount,
            'appliedFilters' => $appliedFilters,
            'db' => $db,
        ]));
    }

    public function allUsers(Request $request): View
    {
        $query = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoinSub(
                DB::table('receipts')
                    ->selectRaw('user_id, MAX(package) as package, MAX(expiry_date) as expiry_date, MAX(status) as status')
                    ->whereIn('status', ['paid', 'closed'])
                    ->groupBy('user_id'),
                'r',
                'user_details.user_id',
                '=',
                'r.user_id'
            )
            ->where('users.role', '!=', 'admin')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.mobile',
                'users.role',
                'users.status',
                'user_details.*',
                DB::raw('IF((r.expiry_date IS NULL OR r.expiry_date > NOW()) AND r.status = "paid", r.package, "closed") as package')
            );

        // Apply filters
        $filters = [
            'gender' => 'user_details.gender',
            'status' => 'users.status',
            'today' => DB::raw('DATE(users.created_at)'),
            'from_date' => ['users.created_at', '>='],
            'to_date' => ['users.created_at', '<='],
            'specific_date' => DB::raw('DATE(users.created_at)'),
            'mobile' => 'users.mobile',
            'user_id' => 'users.id'
        ];

        foreach ($filters as $param => $column) {
            if ($request->filled($param)) {
                if (is_array($column)) {
                    $query->whereDate($column[0], $column[1], $request->input($param));
                } else {
                    $query->where($column, $request->input($param));
                }
            }
        }

        // Apply search filter based on filter_type
        $filterType = $request->input('filter_type');
        $search = $request->input('search');

        if ($filterType && $search) {
            $query->where(function ($q) use ($filterType, $search) {
                if ($filterType === 'id') {
                    $q->where('users.id', intval($search));
                } elseif ($filterType === 'name') {
                    $q->where('users.name', 'like', "%$search%");
                } elseif ($filterType === 'email') {
                    $q->where('users.email', $search);
                } elseif ($filterType === 'mobile') {
                    $q->where('users.mobile', 'like', "%$search%");
                }
            });
        }

        // Filter by package
        if ($request->filled('package')) {
            $query->whereRaw(
                'LOWER(IF((r.expiry_date IS NULL OR r.expiry_date > NOW()) AND r.status = "paid", r.package, "closed")) = ?',
                [strtolower($request->input('package'))]
            );
        }

        // Paginate results
        $details['users'] = $query->orderByDesc('users.id')->paginate(16);
        return view('admin.users', compact('details'));
    }


}
