<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function Location(Request $request): View
    {
        $searchFields = DB::table('search_fields')->get();
//        $search_type = $request->input('searchby');
//        $search = $request->input('search');
//        $searchDated = $request->input('searchDate');
//        $searchJob = $request->input('searchJob');
//        $searchDosham = $request->input('searchDosham');
//        $searchMarital = $request->input('searchMarital');
//
//        $query = DB::table('users')
//            ->join('user_details', 'users.id', '=', 'user_details.user_id');
//
//        // Handle different search types
//        $query->when($search_type == 'id', function ($q) use ($search) {
//            return $q->where('users.id', $search);
//        })->when($search_type == 'name', function ($q) use ($search) {
//            return $q->where('users.name', 'LIKE', "%{$search}%");
//        })->when($search_type == 'email', function ($q) use ($search) {
//            return $q->where('users.email', 'LIKE', "%{$search}%");
//        })->when($search_type == 'mobile', function ($q) use ($search) {
//            return $q->where('users.mobile', 'LIKE', "%{$search}%");
//        })->when($search_type == 'country', function ($q) use ($search) {
//            return $q->where('user_details.country', 'LIKE', "%{$search}%");
//        })->when($search_type == 'dob', function ($q) use ($searchDated) {
//            return $q->whereDate('user_details.date_of_birth', $searchDated);
//        })->when($search_type == 'job', function ($q) use ($searchJob) {
//            return $q->where('user_details.occupation', $searchJob);
//        })->when($search_type == 'dosham', function ($q) use ($searchDosham) {
//            return $q->where('user_details.dosham', $searchDosham);
//        })->when($search_type == 'marital', function ($q) use ($searchMarital) {
//            return $q->where('user_details.marital_status', $searchMarital);
//        })->when($search_type == 'country', function ($q) use ($search) {
//            return $q->where('user_details.country', "%{$search}%");
//        })->when($search_type == 'state', function ($q) use ($search) {
//            return $q->where('user_details.state', "%{$search}%");
//        })->when($search_type == 'city', function ($q) use ($search) {
//            return $q->where('user_details.city', "%{$search}%");
//        });
//
//        // Default search when no search type is selected
//        if (empty($search_type)) {
//            $query->where(function ($q) use ($search, $searchDated, $searchJob, $searchDosham, $searchMarital) {
//                $q->where('user_details.gender', 'LIKE', "%{$search}%")
//                    ->orWhere('user_details.mother_tongue', 'LIKE', "%{$search}%")
//                    ->orWhere('user_details.religion', 'LIKE', "%{$search}%")
//                    ->orWhere('user_details.country', 'LIKE', "%{$search}%")
//                    ->orWhere('user_details.city', 'LIKE', "%{$search}%")
//                    ->orWhere('user_details.caste', 'LIKE', "%{$search}%")
//                    ->orWhereDate('user_details.date_of_birth', $searchDated)
//                    ->orWhere('user_details.occupation', $searchJob)
//                    ->orWhere('user_details.dosham', $searchDosham)
//                    ->orWhere('user_details.marital_status', $searchMarital);
//            });
//        }
//
//        $profiles = $query->get();

        return view('Testing.test', compact('searchFields'));
    }

    public function search(Request $request)
    {
        $searchFields = DB::table('search_fields')->get();
        $query = DB::table('search_fields');
        foreach ($searchFields as $field) {
            if ($request->filled($field->field_name)) {
                $query->where($field->field_name, 'like', '%' . $request->input($field->field_name) . '%');
            }
        }
        $results = $query->get();
        return view('Testing.test', compact('results'));
    }


    public function getCountries(): JsonResponse
    {
        $countries = DB::table('countries')->get();
        return response()->json($countries);
    }

    public function getStates($country_id): JsonResponse
    {
        $states = DB::table('states')->where('country_id', $country_id)->get();
        return response()->json($states);
    }

    public function getCities($state_id): JsonResponse
    {
        $cities = DB::table('cities')->where('state_id', $state_id)->get();
        return response()->json($cities);
    }


    public function allEducationLevels(): JsonResponse
    {
        $educationLevels  = DB::table('education_level')->get();
        return response()->json($educationLevels);
    }

    public function getStudies($education_level_id): JsonResponse
    {
        $studies = DB::table('education')->where('education_level_id', $education_level_id)->get();
        if ($studies->isEmpty()) {
            return response()->json(['message' => 'No studies found'], 404);
        }
        return response()->json($studies);
    }

    public function allCastes(): JsonResponse
    {
        $castes  = DB::table('castes')->get();
        return response()->json($castes);
    }

    public function getSubCastes($caste_id): JsonResponse
    {
        $sub_castes = DB::table('sub_castes')->where('caste_id', $caste_id)->get();
        if ($sub_castes->isEmpty()) {
            return response()->json(['message' => 'No Sub_caste found'], 404);
        }
        return response()->json($sub_castes);
    }

    public function getOccupationTypes(): JsonResponse
    {
        $occupationTypes = DB::table('occupation_type')->get();
        return response()->json($occupationTypes);
    }

    public function getOccupations($occupation_type_id): JsonResponse
    {
        $occupations = DB::table('occupation')->where('occupation_type_id', $occupation_type_id)->get();
        if ($occupations->isEmpty()) {
            return response()->json(['message' => 'No occupation found'], 404);
        }
        return response()->json($occupations);
    }
    
    public function birthCountries(): JsonResponse
    {
        $countries = DB::table('countries')->get();
        return response()->json($countries);
    }

    public function birthStates($countryId): JsonResponse
    {
        $states = DB::table('states')->where('country_id', $countryId)->get();
        return response()->json($states);
    }

    public function birthCities($stateId): JsonResponse
    {
        $cities = DB::table('cities')->where('state_id', $stateId)->get();
        return response()->json($cities);
    }
}
