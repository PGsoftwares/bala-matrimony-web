<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    protected ApiHelperController $apiHelper;

    public function __construct(ApiHelperController $apiHelper)
    {
        $this->apiHelper = $apiHelper;
    }

    public function searchProfile(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $filters = $request->only([
            'mother_tongue', 'marital_status', 'skin_tone', 'height', 'body_type', 'eating_habit',
            'drinking_habit', 'smoking_habit', 'religion', 'caste', 'sub_caste', 'qualification',
            'education', 'occupation_type', 'occupation', 'country', 'state', 'city', 'monthly_income',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham', 'min_age', 'max_age',
            'work_country', 'visa_status', 'ethnicity', 'nationality', 'employed_in', 'sort_by'
        ]);

        $perPage = (int) $request->input('per_page', 15);
        $currentPage = (int) $request->input('page_no', 1);
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $sortBy = $request->input('sort_by');

        return $this->apiHelper->profileSearch($userId, $filters, $sortBy, $perPage, $currentPage);
    }

    public function searchProfileById(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $searchId = $request->input('search_id');

        $response = $this->apiHelper->profileSearch($userId, ['user_id' => $searchId]);
        $profiles = $response->getData()->search_profiles ?? [];
        $total = $response->getData()->search_profiles_count ?? 0;

        return response()->json([
            'message' => $total > 0 ? 'Profile retrieved successfully.' : 'No profile found.',
            'Id_search_profiles' => $profiles,
            'total' => $total,
        ]);
    }


    public function horoscopeSearchApi(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $filters = $request->only([
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham'
        ]);

        $perPage = (int) $request->input('per_page', 15);
        $currentPage = (int) $request->input('page_no', 1);
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $response = $this->apiHelper->profileSearch($userId, $filters, null, $perPage, $currentPage);
        $data = $response->getData();
        $profiles = $data->search_profiles ?? [];
        $totalCount = $data->search_profiles_count ?? 0;
        $totalPages = $data->total_pages ?? 0;

        return response()->json([
            'message' => $totalCount > 0 ? 'Horoscope Profiles retrieved successfully.' : 'No profiles found.',
            'horoscope_search' => $profiles,
            'horoscope_search_count' => count($profiles),
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
        ]);
    }

    public function aiSearchApi(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $userDetails = DB::table('user_details')
            ->where('user_id', $userId)
            ->select('gender')
            ->first();

        if (!$userDetails) {
            return response()->json([
                'hundred_percentage' => [],
                'seventyFive_percentage' => [],
                'fifty_percentage' => [],
                'twentyFive_percentage' => []
            ]);
        }

        $userGender = strtolower($userDetails->gender);
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';

        // Subquery to get the latest receipt per user
        $latestReceipts = DB::table('receipts')
            ->select('user_id', DB::raw('MAX(created_at) as latest_receipt_date'))
            ->groupBy('user_id');

        // Base query to fetch profiles
        $profilesQuery = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('education_level', 'education_level.id', '=', 'user_details.qualification')
            ->leftJoin('occupation_type', 'occupation_type.id', '=', 'user_details.occupation_type')
            ->leftJoinSub($latestReceipts, 'latest_receipts', function ($join) {
                $join->on('user_details.user_id', '=', 'latest_receipts.user_id');
            })
            ->leftJoin('receipts', function ($join) {
                $join->on('user_details.user_id', '=', 'receipts.user_id')
                    ->on('receipts.created_at', '=', 'latest_receipts.latest_receipt_date');
            })
            ->where('user_details.gender', $oppositeGender)
            ->orderBy('user_details.created_at', 'desc')
            ->select(
                'users.id', 'users.name',
                DB::raw("CONCAT('" . asset('Profile Image') . "/', user_details.profile_image) AS profile_image"),
                'user_details.height', 'user_details.city', 'user_details.dob', 'user_details.gender',
                'occupation_type.name as occupation_type',
                DB::raw('COALESCE(receipts.package, "No Package") AS package')
            );

        // **Apply Filters (Supports Array Input)**
        $filters = [
            'mother_tongue', 'marital_status', 'skin_tone', 'height', 'body_type',
            'drinking_habit', 'smoking_habit', 'religion', 'caste', 'sub_caste',
            'qualification', 'education', 'occupation_type', 'occupation',
            'country', 'state', 'city', 'rashi', 'nakshatra', 'lagnam', 'padam',
            'kulam', 'gothram', 'dosham'
        ];

        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $values = $request->input($filter);
                if (is_array($values) && count($values) > 0) {
                    $profilesQuery->whereIn('user_details.' . $filter, $values);
                } else {
                    $profilesQuery->where('user_details.' . $filter, $values);
                }
                $filterApplied = true;
            }
        }

        // **Age Filter with min_age & max_age**
        if ($request->filled('min_age') && $request->filled('max_age')) {
            $minAge = (int) $request->input('min_age');
            $maxAge = (int) $request->input('max_age');
            $profilesQuery->whereBetween(DB::raw("(YEAR(CURDATE()) - YEAR(user_details.dob))"), [$minAge, $maxAge]);
            $filterApplied = true;
        }

        // **If No Filters Applied, Return Empty Response**
        if (!$filterApplied) {
            return response()->json([
                'hundred_percentage' => [],
                'seventyFive_percentage' => [],
                'fifty_percentage' => [],
                'twentyFive_percentage' => []
            ]);
        }

        // **Fetch Profiles & Calculate Match Percentage**
        $profiles = $profilesQuery->get()->map(function ($profile) use ($request) {
            $profile->age = Carbon::parse($profile->dob)->age;
            $profile->match_percentage = $this->calculateMatchPercentage($profile, $request->all());
            return $profile;
        });

        // **Group Profiles by Match Percentage**
        $groupedProfiles = [
            'hundred_percentage' => $profiles->filter(fn($profile) => $profile->match_percentage === 100)->values(),
            'seventyFive_percentage' => $profiles->filter(fn($profile) => $profile->match_percentage >= 75 && $profile->match_percentage < 100)->values(),
            'fifty_percentage' => $profiles->filter(fn($profile) => $profile->match_percentage >= 50 && $profile->match_percentage < 75)->values(),
            'twentyFive_percentage' => $profiles->filter(fn($profile) => $profile->match_percentage >= 25 && $profile->match_percentage < 50)->values(),
        ];

        return response()->json($groupedProfiles);
    }

    /**
     * **Calculate Match Percentage**
     */
    private function calculateMatchPercentage($profile, $filters)
    {
        $totalCriteria = 0;
        $matchedCriteria = 0;

        // **Check each filter & match with profile data**
        foreach ($filters as $key => $value) {
            if (!in_array($key, ['user_id', 'min_age', 'max_age'])) {
                if (!isset($profile->$key)) {
                    continue;
                }

                $totalCriteria++;
                if (is_array($value)) {
                    if (!empty($profile->$key) && in_array($profile->$key, $value)) {
                        $matchedCriteria++;
                    }
                } else {
                    if (!empty($profile->$key) && $profile->$key == $value) {
                        $matchedCriteria++;
                    }
                }
            }
        }

        return $totalCriteria > 0 ? round(($matchedCriteria / $totalCriteria) * 100) : 0;
    }

}