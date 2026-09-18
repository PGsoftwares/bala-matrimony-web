<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\WebController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SearchController extends Controller
{

    protected  $webController;

    public function __construct(WebController $webController)
    {
        $this->webController = $webController;
    }

    public function horoscopeSearch(Request $request): string
    {
        $userId = Auth::id();
        $packageDetails = DataController::getUserPackageDetails($userId);
        $userPackageValue = $packageDetails['package'];
        $isPackageValid = $packageDetails['is_active'];

        // Get logged-in user's details
        $userAndUserDetails = DataController::getUserDetails($userId);
        $oppositeGender = DataController::getOppositeGender($userAndUserDetails->gender);

        // Start query for matching profiles
        $profilesQuery = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('user_details.gender', $oppositeGender)
            ->where('users.status', '=', 'active')
            ->orderBy('user_details.created_at', 'desc')
            ->select('user_details.*', 'users.*', 'settings.profile_picture_visibility', 'settings.name_visibility');

        // Available filters
        $filters = [
            'search_id'   => 'user_details.user_id',
            'rashi'       => 'user_details.rashi',
            'nakshatra'   => 'user_details.nakshatra',
            'lagnam'      => 'user_details.lagnam',
            'padam'       => 'user_details.padam',
            'kulam'       => 'user_details.kulam',
            'gothram'     => 'user_details.gothram',
            'dosham'      => 'user_details.dosham',
        ];

        // Applied filters for UI
        $appliedFilters = [];
        foreach ($filters as $input => $column) {
            if ($request->filled($input)) {
                $profilesQuery->where($column, $request->input($input));
                $appliedFilters[$input] = $request->input($input);
            }
        }

        // Paginate and format result
        $profiles = $profilesQuery->paginate(10);
        $profiles->getCollection()->transform(function ($profile) {
            $profile->age = Carbon::parse($profile->dob)->age;
            $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, null, $profile->gender);
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, null);
            return $profile;
        });


        $profilesCount = $profiles->total();
        $metaTags = DataSharedController::MetaData('horoscope-search');
        $db = DataSharedController::getDatabases();

        return view('web.horoscope-search', [
            'userAndUserDetails' => $userAndUserDetails,
            'profiles'           => $profiles,
            'profilesCount'      => $profilesCount,
            'appliedFilters'     => $appliedFilters,
            'message'            => $profilesCount === 0 ? 'No profiles found for the selected criteria.' : null,
            'metaTags'           => $metaTags,
            'userPackageValue'   => $userPackageValue,
            'isPackageValid'     => $isPackageValid,
            'db'                 => $db,
        ]);
    }



    public function aiSearch(Request $request): View
    {
        $userId = Auth::id();
        $userPackage = DB::table('receipts')
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->orderBy('expiry_date', 'desc')
            ->first();

        $isPackageValid = $userPackage && $userPackage->status === 'paid' && (is_null($userPackage->expiry_date) || $userPackage->expiry_date > now());
        $userPackageValue = $userPackage ? $userPackage->package : '';

        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        $userGender = strtolower($userAndUserDetails->gender);
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';

        $recentUserDetailsData = $this->webController->getRecentUserDetails();
        $recentUserDetails = $recentUserDetailsData['recentUserDetails'] ?? [];

        // Base query to fetch profiles
        $profilesQuery = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings','user_details.user_id','=','settings.user_id')
            ->where('user_details.gender', $oppositeGender)
            ->orderBy('user_details.created_at', 'desc')
            ->select('user_details.*', 'users.*', 'settings.profile_picture_visibility');

        $filters = [
            'mother_tongue', 'age', 'marital_status', 'skin_tone', 'height', 'body_type',
            'drinking_habit', 'smoking_habit', 'religion', 'caste', 'sub_caste', 'qualification',
            'education', 'occupation_type', 'occupation', 'country', 'state', 'city',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham'
        ];

        $appliedFilters = [];
        $totalFilters = 0;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $totalFilters++;
                if ($filter === 'age') {
                    $ageRange = explode(' to ', $request->input('age'));
                    $minA = (int)($ageRange[0] ?? 18);
                    $maxA = (int)($ageRange[1] ?? 80);
                    $profilesQuery->addSelect(DB::raw("IF(user_details.dob IS NOT NULL AND TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) BETWEEN {$minA} AND {$maxA}, 1, 0) as match_{$filter}"));
                } else {
                    $profilesQuery->addSelect(DB::raw("IF(user_details.{$filter} = '{$request->input($filter)}', 1, 0) as match_{$filter}"));
                }
                $appliedFilters[$filter] = $request->input($filter);
            }
        }

        $profiles = $profilesQuery->get()->map(function ($profile) use ($filters, $totalFilters) {
            $matchCount = 0;

            foreach ($filters as $filter) {
                if (isset($profile->{"match_{$filter}"})) {
                    $matchCount += $profile->{"match_{$filter}"};
                }
            }

            $profile->match_percentage = $totalFilters > 0 ? round(($matchCount / $totalFilters) * 100) : 0;
            $profile->age = Carbon::parse($profile->dob)->age;

            return $profile;
        });

        $filteredProfiles = $profiles->filter(function ($profile) {
            return $profile->match_percentage > 0;
        });

        $profilesCount = $filteredProfiles->count();

        $db = DataSharedController::getDatabases();
        $metaTags = DataSharedController::MetaData('ai_search');

        return view('web.ai_search', array_merge([
            'userAndUserDetails' => $userAndUserDetails,
            'profiles' => $filteredProfiles,
            'profilesCount' => $profilesCount,
            'appliedFilters' => $appliedFilters,
            'metaTags' => $metaTags,
            'userPackageValue' => $userPackageValue,
            'isPackageValid' => $isPackageValid,
            'db' => $db,
            'recentUserDetails' => $recentUserDetails,
        ]));
    }

}
