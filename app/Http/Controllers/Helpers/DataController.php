<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public static function getUserDetails($userId)
    {
        return DB::table('users')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();
    }

    public static function getOppositeGender(string $gender): string
    {
        $gender = strtolower($gender);
        return $gender === 'male' ? 'female' : 'male';
    }

    public static function getUserPackageDetails($userId): array
    {
        $userPackage = DB::table('receipts')
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->orderByDesc('id')
            ->first();

        return [
            'package' => $userPackage->package ?? '',
            'is_active' => $userPackage && (is_null($userPackage->expiry_date) || $userPackage->expiry_date >= now()),
            'receipt' => $userPackage
        ];
    }

    public static function otherProfileReceipts($profileId): Collection
    {
        return DB::table('receipts')
            ->where('user_id', $profileId)
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public static function applyProfilePrivacy(&$profile, $userPackageValue): void
    {
        $profile->age = Carbon::parse($profile->dob)->age;
        $profile->profile_image = ApiHelperController::ImageUrl(
            $profile->profile_image,
            $profile->profile_picture_visibility,
            $userPackageValue,
            $profile->gender
        );
        $profile->name = ApiHelperController::privacyData(
            $profile->name,
            $profile->name_visibility,
            $userPackageValue
        );
    }

    public static function getPreferenceBasedProfiles($userId, $gender, $userPackageValue): Collection
    {
        $prefs = DB::table('set_preferences')->where('user_id', $userId)->first();

        $query = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings','user_details.user_id','=','settings.user_id')
            ->where('user_details.gender', $gender)
            ->where('users.status', '=', 'active')
            ->select('user_details.*', 'users.*', 'settings.profile_picture_visibility', 'settings.name_visibility');

        if ($prefs) {
            /* Age filter */
            if ($prefs->min_age) {
                $query->whereNotNull('user_details.dob')
                    ->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) >= ?", [$prefs->min_age]);
            }
            if ($prefs->max_age) {
                $query->whereNotNull('user_details.dob')
                    ->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) <= ?", [$prefs->max_age]);
            }

            /* Exact match fields */
            foreach ([
                         'caste', 'dosham', 'education', 'country', 'state', 'city',
                         'mother_tongue', 'marital_status', 'skin_tone',
                         'body_type', 'drinking_habit', 'smoking_habit', 'eating_habit',
                         'physical_status', 'religion', 'qualification', 'occupation_type',
                         'occupation', 'employed_in', 'rashi', 'nakshatra', 'lagnam',
                         'padam', 'kulam', 'gothram'
                     ] as $field) {
                if (!empty($prefs->$field)) {
                    $query->where("user_details.$field", $prefs->$field);
                }
            }

            /* Height range filter */
            if (!empty($prefs->height_from)) {
                $query->where('user_details.height', '>=', $prefs->height_from);
            }
            if (!empty($prefs->height_to)) {
                $query->where('user_details.height', '<=', $prefs->height_to);
            }

            /* Monthly income range filter */
            if (!empty($prefs->monthly_income_from)) {
                $query->where('user_details.monthly_income', '>=', $prefs->monthly_income_from);
            }
            if (!empty($prefs->monthly_income_to)) {
                $query->where('user_details.monthly_income', '<=', $prefs->monthly_income_to);
            }
        }

        $profiles = $query->limit(10)->get();
        foreach ($profiles as $profile) {
            self::applyProfilePrivacy($profile, $userPackageValue);
        }
        return $profiles;
    }


    public static function getRecentProfiles($gender, $userPackageValue): Collection
    {
        $profiles = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('user_details.gender', $gender)
            ->where('users.status', '=', 'active')
            ->orderByDesc('user_details.created_at')
            ->select('user_details.*', 'users.*', 'settings.profile_picture_visibility', 'settings.name_visibility')
            ->limit(10)
            ->get();

        foreach ($profiles as $profile) {
            self::applyProfilePrivacy($profile, $userPackageValue);
        }
        return $profiles;
    }

    public static function getProfileViewsWithPrivacy($userId, $type, $userPackageValue): array
    {
        $query = DB::table('profile_views')
            ->join('user_details', function ($join) use ($type) {
                $joinField = $type === 'viewed_by_me' ? 'viewer_id' : 'viewed_id';
                $join->on("profile_views.$joinField", '=', 'user_details.user_id');
            })
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->where('users.status', '=', 'active');

        if ($type === 'viewed_by_me') {
            $query->where('profile_views.viewed_id', $userId);
        } else {
            $query->where('profile_views.viewer_id', $userId);
        }

        $profiles = $query->select('user_details.*', 'users.*',
            'settings.profile_picture_visibility', 'settings.name_visibility'
        )->distinct('user_details.user_id')->get();

        foreach ($profiles as $profile) {
            self::applyProfilePrivacy($profile, $userPackageValue);
        }
        return [
            'profiles' => $profiles,
            'count' => count($profiles)
        ];
    }

    public static function getContactViews($userId, string $type, $userPackageValue): Collection
    {
        $query = DB::table('contact_requests')
            ->join('user_details', function ($join) use ($type) {
                if ($type === 'viewed_by_me') {
                    $join->on('contact_requests.profile_id', '=', 'user_details.user_id');
                } else {
                    $join->on('contact_requests.user_id', '=', 'user_details.user_id');
                }
            })
            ->join('users', 'user_details.user_id', '=', 'users.id');

        if ($type === 'viewed_by_me') {
            $query->where('contact_requests.user_id', $userId);
        } else {
            $query->where('contact_requests.profile_id', $userId);
        }

        $profiles = $query->select('user_details.*', 'users.*')
            ->distinct('user_details.user_id')
            ->get();
        return $profiles;
    }

    /**
     * Parse stored property details string or JSON into an associative array [property_name => count].
     * Supports formats:
     * - "Own House: 2, Plots: 1"
     * - "1 Own House, 2 Plots"
     * - "Own House (2), Plots (1)"
     * - "Own House - 2, Plots - 1"
     * - Legacy format "Rental House, Apartment house" (defaults count to 1)
     * - JSON string or array
     *
     * @param mixed $propertyDetails
     * @return array
     */
    public static function parsePropertyDetails($propertyDetails): array
    {
        if (empty($propertyDetails)) {
            return [];
        }

        if (is_array($propertyDetails)) {
            $result = [];
            foreach ($propertyDetails as $key => $val) {
                if (is_array($val)) {
                    $name = $val['name'] ?? $val['property_name'] ?? $val['property'] ?? $val['title'] ?? (is_string($key) ? $key : null);
                    $count = $val['count'] ?? $val['value'] ?? $val['no_of_property'] ?? $val['quantity'] ?? $val['total'] ?? $val['no'] ?? '1';
                    if ($name) {
                        $result[trim((string)$name)] = (string)$count;
                    }
                } elseif (!is_numeric($key)) {
                    $result[trim((string)$key)] = (string)$val;
                } elseif (is_string($val) && trim($val) !== '') {
                    $result[trim($val)] = '1';
                }
            }
            return $result;
        }

        if (is_string($propertyDetails)) {
            $json = json_decode($propertyDetails, true);
            if (is_array($json)) {
                return $json;
            }

            $parts = explode(',', $propertyDetails);
            $result = [];
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part === '') {
                    continue;
                }

                if (preg_match('/^(.+?)\s*:\s*(\d+)$/', $part, $m)) {
                    $result[trim($m[1])] = (string)intval($m[2]);
                } elseif (preg_match('/^(.+?)\s*\(\s*(\d+)\s*\)$/', $part, $m)) {
                    $result[trim($m[1])] = (string)intval($m[2]);
                } elseif (preg_match('/^(\d+)\s*[-:]*\s*(.+)$/', $part, $m)) {
                    $result[trim($m[2])] = (string)intval($m[1]);
                } elseif (preg_match('/^(.+?)\s*-\s*(\d+)$/', $part, $m)) {
                    $result[trim($m[1])] = (string)intval($m[2]);
                } else {
                    $result[$part] = '1';
                }
            }
            return $result;
        }

        return [];
    }

    /**
     * Format property details input into a clean string for storage.
     * Takes an associative array [property_name => count], array of objects/arrays, indexed array, JSON string, or string.
     *
     * @param mixed $input
     * @return string|null
     */
    public static function formatPropertyDetails($input): ?string
    {
        if (empty($input)) {
            return null;
        }

        if (is_string($input)) {
            $trimmed = trim($input);
            if ($trimmed === '') {
                return null;
            }
            if ((str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) ||
                (str_starts_with($trimmed, '{') && str_ends_with($trimmed, '}'))) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                    $input = $decoded;
                } else {
                    return $trimmed;
                }
            } else {
                return $trimmed;
            }
        }

        if (is_array($input)) {
            $formatted = [];
            foreach ($input as $key => $val) {
                if (is_array($val)) {
                    $name = $val['name'] ?? $val['property_name'] ?? $val['property'] ?? $val['title'] ?? (is_string($key) ? $key : null);
                    $count = $val['count'] ?? $val['value'] ?? $val['no_of_property'] ?? $val['quantity'] ?? $val['total'] ?? $val['no'] ?? null;
                    
                    if ($name) {
                        $name = trim((string)$name);
                        $countVal = ($count !== null && is_numeric($count)) ? intval($count) : (is_string($count) ? trim($count) : '');
                        if ($countVal !== '' && $countVal !== 0 && $countVal !== '0') {
                            $formatted[] = $name . ': ' . $countVal;
                        } elseif ($count === null || $count === '') {
                            $formatted[] = $name;
                        }
                    }
                } elseif (!is_numeric($key)) {
                    $count = is_numeric($val) ? intval($val) : (is_string($val) ? trim($val) : '');
                    if ($count !== '' && $count !== 0 && $count !== '0') {
                        $formatted[] = trim((string)$key) . ': ' . $count;
                    }
                } else {
                    if (is_string($val) && trim($val) !== '') {
                        $formatted[] = trim($val);
                    }
                }
            }
            return !empty($formatted) ? implode(', ', $formatted) : null;
        }

        return null;
    }

}
