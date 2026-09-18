<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DropdownController extends Controller
{
    public function getStates(Request $request): JsonResponse
    {
        $states = DB::table('states')
            ->whereIn('country_id', function ($query) use ($request) {
                $query->select('id')->from('countries')->where('name', $request->input('country'));
            })
            ->pluck('name');

        return response()->json($states);
    }

    public function getCities(Request $request): JsonResponse
    {
        $cities = DB::table('cities')
            ->whereIn('state_id', function ($query) use ($request) {
                $query->select('id')->from('states')->where('name', $request->input('state'));
            })
            ->pluck('name');

        return response()->json($cities);
    }

    public function getOccupations(Request $request): JsonResponse
    {
        $occupations = DB::table('occupation')
            ->whereIn('occupation_type_id', function ($query) use ($request) {
                $query->select('id')->from('occupation_type')->where('name', $request->input('occupation_type'));
            })
            ->pluck('name');

        return response()->json($occupations);
    }

    public function getEducation(Request $request): JsonResponse
    {
        $educations = DB::table('education')
            ->whereIn('education_level_id', function ($query) use ($request) {
                $query->select('id')->from('education_level')->where('name', $request->input('education_level'));
            })
            ->pluck('name');

        return response()->json($educations);
    }

    public function getSubCastes(Request $request): JsonResponse
    {
        $subCastes = DB::table('sub_castes')
            ->whereIn('caste_id', function ($query) use ($request) {
                $query->select('id')->from('castes')->where('name', $request->input('caste'));
            })
            ->pluck('name');

        return response()->json($subCastes);
    }


    public static function getLocationData($countryName = null, $stateName = null): array
    {
        // Fetch countries
        $countries = DB::table('countries')->get();

        $states = collect(); // Initialize empty collection for states
        if ($countryName) {
            $states = DB::table('states')
                ->where('country_id', function ($query) use ($countryName) {
                    $query->select('id')->from('countries')->where('name', $countryName);
                })
                ->get();
        }

        $cities = collect(); // Initialize empty collection for cities
        if ($stateName) {
            $cities = DB::table('cities')
                ->where('state_id', function ($query) use ($stateName) {
                    $query->select('id')->from('states')->where('name', $stateName);
                })
                ->get();
        }

        return [
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities
        ];
    }


    public static function getCombinedLocationData($userDetails): array
    {
        // Regular location data
        $locationData = self::getLocationData(
            $userDetails->country ?? null,
            $userDetails->state ?? null
        );

        // Birth location data
        $birthLocationData = self::getLocationData(
            $userDetails->birth_country ?? null,
            $userDetails->birth_state ?? null
        );

        // Work Location data
        $workLocationData = self::getLocationData(
            $userDetails->work_country ?? null,
        );

        return [
            'countries'      => $locationData['countries'] ?? collect(),
            'states'         => $locationData['states'] ?? collect(),
            'cities'         => $locationData['cities'] ?? collect(),
            'birth_countries'=> $birthLocationData['countries'] ?? collect(),
            'birth_states'   => $birthLocationData['states'] ?? collect(),
            'birth_cities'   => $birthLocationData['cities'] ?? collect(),
            'work_countries'   => $workLocationData['countries'] ?? collect(),
        ];
    }

    public static function getDropdownData($qualificationName = null, $educationName = null, $occupationTypeName = null, $occupationName = null, $caste = null, $subCaste = null): array
    {
        // Fetch education levels
        $educationLevels = DB::table('education_level')->get();

        // Fetch education types
        $educations = collect();
        if ($qualificationName) {
            $educations = DB::table('education')
                ->where('education_level_id', function ($query) use ($qualificationName) {
                    $query->select('id')->from('education_level')->where('name', $qualificationName);
                })
                ->get();
        }

        // Fetch occupation types
        $occupationTypes = DB::table('occupation_type')->get();

        // Fetch occupations
        $occupations = collect();
        if ($occupationTypeName) {
            $occupations = DB::table('occupation')
                ->where('occupation_type_id', function ($query) use ($occupationTypeName) {
                    $query->select('id')->from('occupation_type')->where('name', $occupationTypeName);
                })
                ->get();
        }

        // Fetch castes
        $castes = DB::table('castes')->get();

        // Fetch subCastes correctly based on the caste name
        $subCastes = collect();
        if ($caste) {
            $subCastes = DB::table('sub_castes')
                ->where('caste_id', function ($query) use ($caste) {
                    $query->select('id')->from('castes')->where('name', $caste); // Fetch caste_id from 'castes'
                })
                ->get();
        }

        return [
            'educationLevels' => $educationLevels,
            'educations' => $educations,
            'occupationTypes' => $occupationTypes,
            'occupations' => $occupations,
            'castes' => $castes,
            'subCastes' => $subCastes
        ];
    }
}
