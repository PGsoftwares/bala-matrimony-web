<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ApiHelperController extends Controller
{
    public static function profileSearch($userId, $filters = [], $sortBy = null, $perPage = null, $currentPage = null): JsonResponse
    {
        $user = DB::table('user_details')->where('user_id', $userId)
            ->select('gender', 'city', 'latitude', 'longitude')->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'search_profiles_count' => 0,
                'current_page' => $currentPage ?? 1,
                'total_pages' => 0,
                'search_profiles' => [],
            ], 200);
        }

        $oppositeGender = strtolower($user->gender) === 'male' ? 'female' : 'male';
        $authPackageData = DataController::getUserPackageDetails($userId);
        $authReceipt = $authPackageData['is_active'] ?? '';

        $query = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->leftJoin('castes', 'user_details.caste', '=', 'castes.id')
            ->leftJoin('occupation_type', 'user_details.occupation_type', '=', 'occupation_type.id')
            ->leftJoin('education_level', 'user_details.qualification', '=', 'education_level.id')
            ->where('user_details.gender', $oppositeGender)
            ->where('users.status', '!=', 'deactivated')
            ->select([
                'users.id',
                'users.name',
                'users.email_verified_at',
                'users.photo_verified_at',
                'user_details.profile_image',
                'user_details.marital_status',
                DB::raw('COALESCE(castes.name, user_details.caste) as caste'),
                'user_details.employed_in',
                'user_details.height',
                'user_details.weight',
                'user_details.city',
                'user_details.latitude',
                'user_details.longitude',
                'user_details.dob',
                'user_details.gender',
                DB::raw('COALESCE(occupation_type.name, user_details.occupation_type) as occupation_type'),
                'user_details.monthly_income',
                DB::raw('COALESCE(education_level.name, user_details.qualification) as qualification'),
                'user_details.education',
                'user_details.horoscope_image',
                'user_details.marriage_timeline',
                'user_details.created_at',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'settings.date_of_birth_visibility',
            ]);

        // Dynamic filters (excluding special keys)
        $excludedKeys = [
            'min_age', 'max_age', 'weight_from', 'weight_to', 'sort_by', 'page_no', 'per_page', 'user_id'
        ];

        foreach ($filters as $key => $value) {
            if (in_array($key, $excludedKeys, true) || $value === '' || $value === null) {
                continue;
            }

            if ($key === 'caste') {
                if (is_numeric($value)) {
                    $query->where(function ($q) use ($value) {
                        $q->where('user_details.caste', $value)
                          ->orWhere('castes.id', $value);
                    });
                } else {
                    $query->where(function ($q) use ($value) {
                        $q->where('user_details.caste', $value)
                          ->orWhere('castes.name', $value);
                    });
                }
                continue;
            }

            if ($key === 'qualification') {
                if (is_numeric($value)) {
                    $query->where(function ($q) use ($value) {
                        $q->where('user_details.qualification', $value)
                          ->orWhere('education_level.id', $value);
                    });
                } else {
                    $query->where(function ($q) use ($value) {
                        $q->where('user_details.qualification', $value)
                          ->orWhere('education_level.name', $value);
                    });
                }
                continue;
            }

            if ($key === 'occupation_type') {
                if (is_numeric($value)) {
                    $query->where(function ($q) use ($value) {
                        $q->where('user_details.occupation_type', $value)
                          ->orWhere('occupation_type.id', $value);
                    });
                } else {
                    $query->where(function ($q) use ($value) {
                        $q->where('user_details.occupation_type', $value)
                          ->orWhere('occupation_type.name', $value);
                    });
                }
                continue;
            }

            $column = "user_details.$key";

            is_array($value)
                ? $query->whereIn($column, $value)
                : $query->where($column, $value);
        }

        // Filter by user_id if specified (e.g. for searchProfileById)
        if (!empty($filters['user_id'])) {
            $query->where('users.id', $filters['user_id']);
        }

        // Age filtering
        if (!empty($filters['min_age']) || !empty($filters['max_age'])) {
            $minAge = (int)($filters['min_age'] ?? 18);
            $maxAge = (int)($filters['max_age'] ?? 80);

            $query->whereNotNull('user_details.dob')
                  ->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
        }

        // Weight filtering
        if (!empty($filters['weight_from']) || !empty($filters['weight_to'])) {
            $weightFrom = (int)($filters['weight_from'] ?? 0);
            $weightTo = (int)($filters['weight_to'] ?? 200);

            $query->whereBetween('user_details.weight', [$weightFrom, $weightTo]);
        }

        // Determine sort_by
        $effectiveSortBy = $sortBy ?? ($filters['sort_by'] ?? null);

        // Apply sort_by logic
        switch ($effectiveSortBy) {
            case 'nearby':
                // Nearby Matches: ORDER BY (profiles.city = (SELECT city FROM users WHERE id = :user_id)) DESC, profiles.city ASC
                $query->orderByRaw('(user_details.city IS NOT NULL AND user_details.city != "" AND user_details.city = (SELECT ud2.city FROM user_details AS ud2 WHERE ud2.user_id = ? LIMIT 1)) DESC, (user_details.city IS NULL OR user_details.city = "") ASC, user_details.city ASC, user_details.id DESC', [$userId]);
                break;

            case 'age_asc':
                // Age Order: ORDER BY profiles.age ASC, profiles.dob DESC
                $query->orderByRaw('user_details.dob IS NULL ASC, TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) ASC, user_details.dob DESC, user_details.id DESC');
                break;

            case 'created_at_desc':
                // Registration Date: ORDER BY profiles.created_at DESC
                $query->orderBy('user_details.created_at', 'desc')->orderBy('user_details.id', 'desc');
                break;

            case 'with_photo':
                // Matches with Photo: WHERE (profiles.profile_image IS NOT NULL AND profiles.profile_image != '' AND profiles.profile_image NOT LIKE '%default%')
                $query->whereNotNull('user_details.profile_image')
                    ->where('user_details.profile_image', '!=', '')
                    ->where('user_details.profile_image', 'not like', '%default%')
                    ->orderBy('user_details.id', 'desc');
                break;

            case 'marriage_timeline':
                // Marriage Readiness: ORDER BY FIELD(profiles.marriage_timeline, 'Within 3 Months', 'Within 6 Months', 'Within 1 Year') ASC
                $query->orderByRaw("(CASE WHEN user_details.marriage_timeline = 'Within 3 Months' THEN 1 WHEN user_details.marriage_timeline = 'Within 6 Months' THEN 2 WHEN user_details.marriage_timeline = 'Within 1 Year' THEN 3 ELSE 4 END) ASC, user_details.id DESC");
                break;

            case 'education_order':
                // Education Qualification Order: ORDER BY profiles.qualification ASC, profiles.employed_in ASC
                $query->orderByRaw('(user_details.qualification IS NULL OR user_details.qualification = "") ASC, user_details.qualification ASC, (user_details.employed_in IS NULL OR user_details.employed_in = "") ASC, user_details.employed_in ASC, user_details.id DESC');
                break;

            case 'with_horoscope':
                // Matches with Horoscope: WHERE (profiles.is_verified = 1 OR (profiles.horoscope_image IS NOT NULL AND profiles.horoscope_image != ''))
                $query->where(function ($q) {
                    $q->whereNotNull('users.email_verified_at')
                      ->orWhereNotNull('users.photo_verified_at')
                      ->orWhere(function ($sq) {
                          $sq->whereNotNull('user_details.horoscope_image')
                             ->where('user_details.horoscope_image', '!=', '')
                             ->where('user_details.horoscope_image', 'not like', '%default%');
                      });
                })->orderBy('user_details.id', 'desc');
                break;

            case 'with_horoscope_and_photo':
                // Matches with Horoscope & Photo: WHERE (profiles.is_verified = 1 OR (profiles.horoscope_image IS NOT NULL AND profiles.horoscope_image != '')) AND (profiles.profile_image IS NOT NULL AND profiles.profile_image != '' AND profiles.profile_image NOT LIKE '%default%')
                $query->where(function ($q) {
                    $q->whereNotNull('users.email_verified_at')
                      ->orWhereNotNull('users.photo_verified_at')
                      ->orWhere(function ($sq) {
                          $sq->whereNotNull('user_details.horoscope_image')
                             ->where('user_details.horoscope_image', '!=', '')
                             ->where('user_details.horoscope_image', 'not like', '%default%');
                      });
                })
                ->whereNotNull('user_details.profile_image')
                ->where('user_details.profile_image', '!=', '')
                ->where('user_details.profile_image', 'not like', '%default%')
                ->orderBy('user_details.id', 'desc');
                break;

            default:
                // Default Fallback: ORDER BY profiles.id DESC
                $query->orderBy('user_details.id', 'desc');
                break;
        }

        $totalCount = (clone $query)->count();

        if ($perPage !== null && $currentPage !== null) {
            $totalPages = $totalCount > 0 ? (int) ceil($totalCount / $perPage) : 0;
            $profiles = $query->offset(($currentPage - 1) * $perPage)->limit($perPage)->get();
        } else {
            $totalPages = $totalCount > 0 ? 1 : 0;
            $currentPage = 1;
            $profiles = $query->get();
        }

        foreach ($profiles as $profile) {
            $userPackage = DataController::getUserPackageDetails($profile->id);
            $profile->package = !empty($userPackage['package']) ? $userPackage['package'] : '';
            try {
                $profile->age = (!empty($profile->dob) && $profile->dob !== '0000-00-00') ? Carbon::parse($profile->dob)->age : null;
            } catch (\Exception $e) {
                $profile->age = null;
            }
            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $authReceipt);
            $profile->dob = ApiHelperController::privacyData($profile->dob, $profile->date_of_birth_visibility, $authReceipt);
            $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, $authReceipt, $profile->gender);
            $profile->is_verified = (bool)(!empty($profile->email_verified_at) || !empty($profile->photo_verified_at));
            $profile->isVerified = $profile->is_verified;
            $profile->marriage_timeline = $profile->marriage_timeline ?? '';
        }

        return response()->json([
            'message' => $totalCount > 0 ? 'Profiles retrieved successfully' : 'No profiles found.',
            'search_profiles_count' => $totalCount,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'search_profiles' => $profiles,
        ]);
    }

    public static function paginate($items, $perPage, $currentPage, $totalCount): LengthAwarePaginator
    {
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = array_slice($items, $offset, $perPage);

        return new LengthAwarePaginator(
            $paginatedItems,
            $totalCount,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function getProfileCompletion($userId): int
    {
        $user = DB::table('users')->where('id', $userId)->first();
        $userDetails = DB::table('user_details')->where('user_id', $userId)->first();

        if (!$user || !$userDetails) {
            return 0;
        }

        $userFields = [
            'name', 'email', 'country_code', 'mobile',
        ];

        $userDetailsFields = [
            'profile_for', 'gender', 'dob', 'birth_time', 'birth_country', 'birth_state', 'birth_city',
            'mother_tongue', 'marital_status', 'skin_tone', 'height', 'weight', 'body_type', 'physical_status',
            'eating_habit', 'drinking_habit', 'smoking_habit', 'religion', 'caste', 'sub_caste',
            'qualification', 'education', 'employed_in', 'occupation_type', 'occupation', 'monthly_income', 'profile_image',
            'father_name', 'father_profession', 'mother_name', 'mother_profession', 'family_type', 'family_status', 'family_values',
            'no_of_brother', 'elder_brother', 'younger_brother', 'elder_married_brother', 'younger_married_brother',
            'no_of_sister', 'elder_sister', 'younger_sister', 'elder_married_sister', 'younger_married_sister',
            'property_details', 'property_info', 'rashi', 'nakshatra', 'gothram', 'dosham', 'horoscope_image',
            'country', 'state', 'city', 'address', 'pin_code', 'work_country', 'visa_status', 'ethnicity', 'nationality',
        ];

        $allFields = array_merge($userFields, $userDetailsFields);

        $totalFields = count($allFields);
        $filledFields = 0;

        foreach ($userFields as $field) {
            if (!empty($user->$field)) {
                $filledFields++;
            }
        }

        foreach ($userDetailsFields as $field) {
            if (!empty($userDetails->$field)) {
                $filledFields++;
            }
        }

        $completionPercentage = ($filledFields / $totalFields) * 100;
        return round($completionPercentage);
    }

    public static function ImageUrl($filename, $ppv, $package, $gender): string
    {
        $imagePath = 'Profile Image/' . $filename;
        $fullImagePath = public_path($imagePath);
        $imageUrl = asset('' . $imagePath);

        $defaultImages = [
            'Male' => asset('asset/img/default/male.webp'),
            'Female' => asset('asset/img/default/female.webp'),
        ];

        $lockedImages = [
            'Male' => asset('asset/img/default/male-lock.png'),
            'Female' => asset('asset/img/default/female-lock.png'),
        ];

        $defaultImage = $defaultImages[$gender] ?? asset('asset/img/default/default.png');
        $lockedImage = $lockedImages[$gender] ?? asset('asset/img/default/locked.png');

        if (!$filename || !file_exists($fullImagePath)) {
            return $defaultImage;
        }

        if ($ppv === null || $ppv === '' || $ppv === 3) {
            return $imageUrl;
        }

        if ($ppv === 0) {
            return $lockedImage;
        }

        if (($ppv === 1 || $ppv === 2) && empty($package)) {
            return $lockedImage;
        }

        return $imageUrl;
    }


    public static function HoroscopeImageUrl($filename, $hpv, $package): string
    {
        $HoroscopeImagePath = 'Horoscope Image/' . $filename;
        $fullImagePath = public_path($HoroscopeImagePath);
        $imageUrl = asset('' .$HoroscopeImagePath);
        $lockedImageUrl = asset('web/assets/default/default-locked.png');
        $defaultImage = asset('');

        if (!$filename || !file_exists($fullImagePath)) {
            return $defaultImage;
        }

        if ($hpv === null || $hpv === '' || $hpv === 3) {
            return $imageUrl;
        }

        if ($hpv === 0) {
            return $lockedImageUrl;
        }

        if (($hpv === 1 || $hpv === 2) && empty($package)) {
            return $lockedImageUrl;
        }
        return $imageUrl;
    }

    public static function privacyData($value, $visibility, $package): string
    {
        $fallback = 'Protected';
        if (is_null($value)) {
            return '';
        }
        if ($visibility === null || $visibility === '' || $visibility === 3 ) {
            return $value;
        }
        if ($visibility === 0) {
            return $fallback;
        }
        if (($visibility === 1 || $visibility === 2) && empty($package)) {
            return $fallback;
        }
        return $fallback;
    }

    public static function isVerified(?string $emailVerifiedAt): bool
    {
        return !empty($emailVerifiedAt);
    }

    public static function getContactUsers($column, $value): Collection
    {
        return DB::table('contact_requests as cr')
            ->join('users as u', $column === 'user_id' ? 'cr.profile_id' : 'cr.user_id', '=', 'u.id')
            ->join('user_details as ud', 'u.id', '=', 'ud.user_id')
            ->leftJoin('settings as s', 'u.id', '=', 's.user_id')
            ->where("cr.$column", $value)
            ->where('u.status', 'active')
            ->orderByDesc('cr.created_at')
            ->get([
                'u.id',
                'u.name',
                'u.email',
                'u.mobile',
                'ud.gender',
                'ud.profile_image',
                'cr.created_at as viewed_on',
                's.profile_picture_visibility', 's.name_visibility', 's.email_visibility', 's.mobile_number_visibility',
            ]);
    }

}
