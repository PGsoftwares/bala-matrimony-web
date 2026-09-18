<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataSharedController extends Controller
{
    public static function MetaData(string $page): ?object
    {
        return DB::table('meta_tags')->where('page', $page)->first();
    }

    public static function getDatabases(): array
    {
        $combinedEducations = DB::table('education_level')
            ->select('education_level.id as level_id', 'education_level.name as level_name')
            ->get()->map(function ($level) {
                $educations = DB::table('education')
                    ->where('education_level_id', $level->level_id)
                    ->select('id', 'name')
                    ->get();
                $level->educations = $educations;
                return $level;
            });
        $combinedOccupations = DB::table('occupation_type')
            ->select('occupation_type.id as type_id', 'occupation_type.name as type_name')
            ->get()->map(function ($type) {
                $occupations = DB::table('occupation')
                    ->where('occupation_type_id', $type->type_id)
                    ->select('id', 'name')
                    ->get();
                $type->occupations = $occupations;
                return $type;
            });

        $Ethnicity = DB::table('ethnicity')->get();
        $Nationality = DB::table('nationality')->get();
        $VisaStatus = DB::table('visa_status')->get();

        return [
            'aboutUs' => DB::table('about_us')->get(),
            'advertisements' => DB::table('advertisement')->get(),
            'blogs' => DB::table('blog')->get(),
            'bodyTypes' => DB::table('body_type')->get(),
            'castes' => DB::table('castes')->pluck('name'),
            'cities' => DB::table('cities')->get(),
            'contactInfo' => DB::table('contact_info')->first(),
            'countries' => DB::table('countries')->pluck('name'),
            'dosham' => DB::table('dosham')->get(),
            'drinkingHabits' => DB::table('drinking_habits')->get(),
            'eatingHabits' => DB::table('eating_habits')->get(),
            'educationLevels' => DB::table('education_level')->pluck('name'),
            'educations' => DB::table('education')->get(),
            'employedIns' => DB::table('employed_in')->get(),
            'gothrams' => DB::table('gothram')->get(),
            'heights' => DB::table('height')->get(),
            'kulams' => DB::table('kulam')->get(),
            'languages' => DB::table('languages')->get(),
            'lagnams' => DB::table('lagnam')->get(),
            'maritalStatuses' => DB::table('marital_status')->get(),
            'nakshatras' => DB::table('stars')->get(),
            'occupationTypes' => DB::table('occupation_type')->pluck('name'),
            'packages' => DB::table('packages')->get(),
            'padams' => DB::table('padam')->get(),
            'paymentInfos' => DB::table('payment_settings')->get(),
            'printInfo' => DB::table('print_info')->first(),
            'privacyPolicy' => DB::table('privacy_policy')->get(),
            'rashies' => DB::table('rashi')->get(),
            'successStories' => DB::table('happy_stories')->get(),
            'refundPolicies' => DB::table('refund_policy')->get(),
            'religions' => DB::table('religion')->get(),
            'salaries' => DB::table('salary')->get(),
            'skinTones' => DB::table('skin_tone')->get(),
            'smokingHabits' => DB::table('smoking_habits')->get(),
            'states' => DB::table('states')->get(),
            'subCastes' => DB::table('sub_castes')->get(),
            'testimonials' => DB::table('testimonials')->get(),
            'termsAndConditions' => DB::table('terms_conditions')->get(),
            'userPermissions' => DB::table('user_permission')->get(),
            'propertyDetails' => DB::table('property_details')->get(),
            'combinedEducations' => $combinedEducations,
            'combinedOccupations' => $combinedOccupations,
            'ethnicity' => $Ethnicity,
            'nationality' => $Nationality,
            'visaStatus' => $VisaStatus,
        ];
    }

    public static function getCustomizeDatabases(): array
    {
        return [
            'cities' => DB::table('cities')->where('state_id', '=' ,'38')->get(),
        ];
    }


    public static function fetchEnabledTables(): array
    {
        $tables = [
            'languages' => 'language',
            'marital_status' => 'maritalStatus',
            'skin_tone' => 'skinTone',
            'height' => 'height',
            'body_type' => 'bodyType',
            'eating_habits' => 'eatingHabit',
            'drinking_habits' => 'drinkingHabit',
            'smoking_habits' => 'smokingHabit',
            'religion' => 'religion',
            'castes' => 'caste',
            'sub_castes' => 'subCaste',
            'salary' => 'salary',
            'education_level' => 'educationLevel',
            'education' => 'education',
            'employed_in' => 'employedIn',
            'occupation_type' => 'occupationType',
            'occupation' => 'occupation',
            'rashi' => 'rashi',
            'stars' => 'star',
            'lagnam' => 'lagnam',
            'padam' => 'padam',
            'kulam' => 'kulam',
            'gothram' => 'gothram',
            'dosham' => 'dosham',
        ];

        $data = [];

        // Fetch the table approvals for all tables in a single query
        $tableApprovals = DB::table('table_approvals')
            ->whereIn('table_name', array_keys($tables))
            ->pluck('status', 'table_name');

        // Loop through the tables and check if each is enabled
        foreach ($tables as $table => $alias) {
            $isTableEnabled = $tableApprovals[$table] ?? null === 'enable';

            // Fetch table data only if it's enabled
            $data[$alias] = $isTableEnabled ? DB::table($table)->get() : [];
            $data['is' . ucfirst($alias) . 'TableEnabled'] = $isTableEnabled;
        }

        return $data;
    }

    public static function getFilteredProfiles(Request $request, $oppositeGender): array
    {
        $appliedFilters = [];

        // If search by user ID
        if ($request->filled('search_id')) {
            $searchId = $request->input('search_id');

            $query = self::baseProfileQuery()
                ->where('user_details.user_id', $searchId)
                ->where('user_details.gender', $oppositeGender);

            $appliedFilters['search_id'] = $searchId;

            return [self::transformProfiles($query->paginate(10)), $appliedFilters];
        }

        // Base query for opposite gender & active status
        $query = self::baseProfileQuery()
            ->where('user_details.gender', $oppositeGender);

        // --- Age filter ---
        $minAge = $request->input('min_age', 0);
        $maxAge = $request->input('max_age', 100);
        if ($request->filled('min_age') || $request->filled('max_age')) {
            $query->whereNotNull('user_details.dob')
                ->whereRaw("TIMESTAMPDIFF(YEAR, user_details.dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
            $appliedFilters['min_age'] = $minAge;
            $appliedFilters['max_age'] = $maxAge;
        }

        // --- Height filter ---
        $heightFrom = $request->input('height_from');
        $heightTo = $request->input('height_to');
        if ($heightFrom !== null && $heightTo !== null) {
            $query->whereBetween('user_details.height', [$heightFrom, $heightTo]);
            $appliedFilters['height_from'] = $heightFrom;
            $appliedFilters['height_to'] = $heightTo;
        }

        // --- Multi-select filters ---
        $multiSelectFilters = [
            'profile_for', 'mother_tongue', 'marital_status', 'skin_tone', 'body_type',
            'drinking_habit', 'smoking_habit', 'religion', 'caste', 'sub_caste', 'qualification',
            'education', 'occupation_type', 'occupation', 'country', 'state', 'city',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham', 'physical_status',
            'ethnicity', 'nationality', 'work_country', 'visa_status'
        ];

        foreach ($multiSelectFilters as $filter) {
            $value = $request->input($filter);
            if (is_array($value) && !empty(array_filter($value))) {
                $query->whereIn("user_details.$filter", $value);
                $appliedFilters[$filter] = $value;
            } elseif ($request->filled($filter)) {
                $query->where("user_details.$filter", $value);
                $appliedFilters[$filter] = $value;
            }
        }

        return [self::transformProfiles($query->paginate(10)), $appliedFilters];
    }

    protected static function baseProfileQuery(): Builder
    {
        $latestReceiptIds = DB::table('receipts')
            ->select('user_id', DB::raw('MAX(id) as latest_id'))
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->groupBy('user_id');

        return DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->leftJoinSub($latestReceiptIds, 'latest_receipt_ids', function ($join) {
                $join->on('user_details.user_id', '=', 'latest_receipt_ids.user_id');
            })
            ->leftJoin('receipts', 'latest_receipt_ids.latest_id', '=', 'receipts.id')
            ->where('users.status', '=', 'active')
            ->orderBy('user_details.created_at', 'desc')
            ->select(
                'users.*',
                'user_details.*',
                'users.email_verified_at',
                'settings.profile_picture_visibility',
                'settings.name_visibility',
                'receipts.package as package'
            );
    }


    protected static function transformProfiles($paginatedProfiles)
    {
        $paginatedProfiles->getCollection()->transform(function ($profile) {
            $profile->age = $profile->dob ? Carbon::parse($profile->dob)->age : null;
            $profile->profile_image = ApiHelperController::ImageUrl(
                $profile->profile_image,
                $profile->profile_picture_visibility,
                null,
                $profile->gender
            );
            $profile->name = ApiHelperController::privacyData(
                $profile->name,
                $profile->name_visibility,
                null
            );

            return $profile;
        });

        return $paginatedProfiles;
    }

    public static function deleteImage(?string $imageName, string $folderPath): bool
    {
        if ($imageName) {
            $fullPath = public_path($folderPath . '/' . $imageName);
            if (File::exists($fullPath)) {
                return File::delete($fullPath);
            }
        }
        return false;
    }

    public static function uploadMultipleImages($files, string $path): array
    {
        $uploadedFiles = [];
        foreach ($files as $file) {
            if ($file->isValid()) {
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $filename);
                $uploadedFiles[] = $filename;
            }
        }
        return $uploadedFiles;
    }



}
