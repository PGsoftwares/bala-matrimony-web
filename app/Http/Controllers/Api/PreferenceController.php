<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreferenceController extends Controller
{
    public function setPreference(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $commaSeparated = function ($input) {
                return is_array($input) ? implode(',', $input) : $input;
            };

            $setPreference = [
                'user_id' => $userId,
                'country' => $commaSeparated($request->input('country')),
                'state' => $commaSeparated($request->input('state')),
                'city' => $commaSeparated($request->input('city')),
                'mother_tongue' => $commaSeparated($request->input('mother_tongue')),
                'min_age' => $request->input('min_age'),
                'max_age' => $request->input('max_age'),
                'marital_status' => $commaSeparated($request->input('marital_status')),
                'skin_tone' => $commaSeparated($request->input('skin_tone')),
                'height_from' => $commaSeparated($request->input('height_from')),
                'height_to' => $commaSeparated($request->input('height_to')),
                'body_type' => $commaSeparated($request->input('body_type')),
                'drinking_habit' => $commaSeparated($request->input('drinking_habit')),
                'smoking_habit' => $commaSeparated($request->input('smoking_habit')),
                'eating_habit' => $commaSeparated($request->input('eating_habit')),
                'physical_status' => $commaSeparated($request->input('physical_status')),
                'religion' => $commaSeparated($request->input('religion')),
                'caste' => $commaSeparated($request->input('caste')),
                'sub_caste' => $commaSeparated($request->input('sub_caste')),
                'qualification' => $commaSeparated($request->input('qualification')),
                'education' => $commaSeparated($request->input('education')),
                'occupation_type' => $commaSeparated($request->input('occupation_type')),
                'occupation' => $commaSeparated($request->input('occupation')),
                'employed_in' => $commaSeparated($request->input('employed_in')),
                'monthly_income_from' => $commaSeparated($request->input('monthly_income_from')),
                'monthly_income_to' => $commaSeparated($request->input('monthly_income_to')),
                'rashi' => $commaSeparated($request->input('rashi')),
                'nakshatra' => $commaSeparated($request->input('nakshatra')),
                'lagnam' => $commaSeparated($request->input('lagnam')),
                'padam' => $commaSeparated($request->input('padam')),
                'kulam' => $commaSeparated($request->input('kulam')),
                'gothram' => $commaSeparated($request->input('gothram')),
                'dosham' => $commaSeparated($request->input('dosham')),
                'work_country' => $commaSeparated($request->input('work_country')),
                'visa_status' => $commaSeparated($request->input('visa_status')),
                'ethnicity' => $commaSeparated($request->input('ethnicity')),
                'nationality' => $commaSeparated($request->input('nationality')),
                'weight_from' => $commaSeparated($request->input('weight_from')),
                'weight_to' => $commaSeparated($request->input('weight_to')),
            ];

            $preference = DB::table('set_preferences')->where('user_id', $userId)->first();

            if ($preference) {
                DB::table('set_preferences')->where('user_id', $userId)->update($setPreference);
                $message = "Preferences updated successfully.";
                $status = 200;
            } else {
                DB::table('set_preferences')->insert($setPreference);
                $message = "Preferences added successfully.";
                $status = 201;
            }

            return response()->json([
                'message' => $message,
                'set_preference' => $setPreference,
            ], $status);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editPreference(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $editPreference = DB::table('set_preferences')
            ->where('user_id', $userId)
            ->select('set_preferences.*')
            ->first();

        if (!$editPreference) {
            $editPreferenceDetails = [
                'user_id' => $userId,
                'country' => '',
                'state' => '',
                'city' => '',
                'mother_tongue' => '',
                'min_age' => '',
                'max_age' => '',
                'marital_status' => '',
                'skin_tone' => '',
                'height_from' => '',
                'height_to' => '',
                'body_type' => '',
                'drinking_habit' => '',
                'smoking_habit' => '',
                'religion' => '',
                'caste' => '',
                'sub_caste' => '',
                'qualification' => '',
                'education' => '',
                'occupation_type' => '',
                'occupation' => '',
                'employed_in' => '',
                'monthly_income_from' => '',
                'monthly_income_to' => '',
                'rashi' => '',
                'nakshatra' => '',
                'lagnam' => '',
                'padam' => '',
                'kulam' => '',
                'gothram' => '',
                'dosham' => '',
            ];

            return response()->json([
                'status' => true,
                'message' => 'User preferences not found.',
                'edit_preference_details' => $editPreferenceDetails,
            ], 200);
        }

        // Convert null values to empty strings
        $editPreferenceDetails = [];
        foreach ($editPreference as $key => $value) {
            $editPreferenceDetails[$key] = $value ?? '';
        }

        return response()->json([
            'status' => true,
            'message' => 'Preference retrieved successfully.',
            'edit_preference_details' => $editPreferenceDetails,
        ]);
    }

}
