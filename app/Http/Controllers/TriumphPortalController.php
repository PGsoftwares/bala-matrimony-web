<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TriumphPortalController extends Controller
{
    public function triumphPortal(Request $request): View
    {
        $usersQuery = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.role', '!=', 'admin');

        if ($request->filled('search')) {
            $searchId = preg_replace('/^(?:BMB|VM)0*/i', '', $request->input('search'));
            $usersQuery->where('users.id', $searchId !== '' ? $searchId : $request->input('search'));
        }

        if ($request->filled('mobile')) {
            $usersQuery->where('users.mobile', 'like', '%' . $request->input('mobile') . '%');
        }

        if ($request->filled('dob')) {
            $usersQuery->whereDate('user_details.dob', $request->input('dob'));
        }

        if ($request->filled('from_date')) {
            $usersQuery->whereDate('users.created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $usersQuery->whereDate('users.created_at', '<=', $request->input('to_date'));
        }

        $users = $usersQuery
            ->select('users.*', 'user_details.dob')
            ->get();

        $user = null;
        if ($request->filled('search')) {
            $searchId = preg_replace('/^(?:BMB|VM)0*/i', '', $request->input('search'));
            $user = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('users.id', $searchId !== '' ? $searchId : $request->input('search'))
                ->select('users.*', 'user_details.*', 'users.id as user_id')
                ->first();
        }

        return view('admin.triumph_portal', compact('users', 'user'));
    }



    // Show specific user history
    public function triumphPortalUser($id): View
    {
        $users = DB::table('users')->where('role', '!=', 'admin')->get();

        $user = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $id)
            ->select(
                'users.*', 'user_details.*'
            )
            ->first();

        $changes = DB::table('triumph_portal')
            ->where('user_id', $id)
            ->orderBy('changed_at', 'asc')
            ->get();

        $fieldMap = [
            'profile_for' => 'Profile for',
            'name' => 'Name',
            'gender' => 'Gender',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'dob' => 'Date of Birth',
            'birth_time' => 'Birth Time',
            'birth_country' => 'Birth Country',
            'birth_state' => 'Birth State',
            'birth_city' => 'Birth City',
            'mother_tongue' => 'Mother Tongue',
            'marital_status' => 'Marital Status',
            'skin_tone' => 'Skin Tone',
            'height' => 'Height',
            'body_type' => 'Body Type',
            'physical_status' => 'Physical Status',
            'eating_habit' => 'Eating Habit',
            'drinking_habit' => 'Drinking Habit',
            'smoking_habit' => 'Smoking Habit',
            'religion' => 'Religion',
            'caste' => 'Caste',
            'sub_caste' => 'Sub Caste',
            'qualification' => 'Qualification',
            'education' => 'Education',
            'occupation_type' => 'Occupation Type',
            'occupation' => 'Occupation',
            'employed_in' => 'Employed In',
            'monthly_income' => 'Monthly Income',
            'father_name' => 'Father Name',
            'father_profession' => 'Father Profession',
            'mother_name' => 'Mother Name',
            'mother_profession' => 'Mother Profession',
            'family_type' => 'Family Type',
            'family_status' => 'Family Status',
            'family_values' => 'Family Values',
            'family_god' => 'Family God',
            'no_of_brother' => 'No. of Brothers',
            'no_of_brother_married' => 'No. of Brothers Married',
            'elder_brother' => 'Elder Brothers',
            'younger_brother' => 'Younger Brothers',
            'elder_married_brother' => 'Elder Married Brothers',
            'younger_married_brother' => 'Younger Married Brothers',
            'no_of_sister' => 'No. of Sisters',
            'no_of_sister_married' => 'No. of Sisters Married',
            'elder_sister' => 'Elder Sisters',
            'younger_sister' => 'Younger Sisters',
            'elder_married_sister' => 'Elder Married Sisters',
            'younger_married_sister' => 'Younger Married Sisters',
            'rashi' => 'Rashi',
            'nakshatra' => 'Nakshatra',
            'lagnam' => 'Lagnam',
            'padam' => 'Padam',
            'kulam' => 'Kulam',
            'gothram' => 'Gothram',
            'dosham' => 'Dosham',
            'address' => 'Address',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'pin_code' => 'Pin code',
        ];

        $dates = [];
        $history = [];
        $changeCounts = [];

        // Step 1: Start with original values
        $initialValues = [
            'Profile for' => $user->profile_for,
            'Name' => $user->name,
            'Gender' => $user->gender,
            'Email' => $user->email,
            'Mobile' => $user->mobile,
            'Date of Birth' => $user->dob ? Carbon::parse($user->dob)->format('d-m-Y') : '',
            'Birth Time' => $user->birth_time,
            'Birth Country' => $user->birth_country,
            'Birth State' => $user->birth_state,
            'Birth City' => $user->birth_city,
            'Mother Tongue' => $user->mother_tongue,
            'Marital Status' => $user->marital_status,
            'Skin Tone' => $user->skin_tone,
            'Height' => $user->height,
            'Body Type' => $user->body_type,
            'Physical Status' => $user->physical_status,
            'Eating Habit' => $user->eating_habit,
            'Drinking Habit' => $user->drinking_habit,
            'Smoking Habit' => $user->smoking_habit,
            'Religion' => $user->religion,
            'Caste' => $user->caste,
            'Sub Caste' => $user->sub_caste,
            'Qualification' => $user->qualification,
            'Education' => $user->education,
            'Occupation Type' => $user->occupation_type,
            'Occupation' => $user->occupation,
            'Employed In' => $user->employed_in,
            'Monthly Income' => $user->monthly_income,
            'Father Name' => $user->father_name,
            'Father Profession' => $user->father_profession,
            'Mother Name' => $user->mother_name,
            'Mother Profession' => $user->mother_profession,
            'Family Type' => $user->family_type,
            'Family Status' => $user->family_status,
            'Family Values' => $user->family_values,
            'Family God' => $user->family_god,
            'No. of Brothers' => $user->no_of_brother,
            'No. of Brothers Married' => $user->no_of_brother_married,
            'Elder Brothers' => $user->elder_brother,
            'Younger Brothers' => $user->younger_brother,
            'Elder Married Brothers' => $user->elder_married_brother,
            'Younger Married Brothers' => $user->younger_married_brother,
            'No. of Sisters' => $user->no_of_sister,
            'No. of Sisters Married' => $user->no_of_sister_married,
            'Elder Sisters' => $user->elder_sister,
            'Younger Sisters' => $user->younger_sister,
            'Elder Married Sisters' => $user->elder_married_sister,
            'Younger Married Sisters' => $user->younger_married_sister,
            'Rashi' => $user->rashi,
            'Nakshatra' => $user->nakshatra,
            'Lagnam' => $user->lagnam,
            'Padam' => $user->padam,
            'Kulam' => $user->kulam,
            'Gothram' => $user->gothram,
            'Dosham' => $user->dosham,
            'Country' => $user->country,
            'State' => $user->state,
            'City' => $user->city,
        ];

        // Step 2: Reverse apply old values to get original values
        foreach ($changes->reverse() as $change) {
            $field = $fieldMap[$change->field_name] ?? $change->field_name;
            $initialValues[$field] = $change->old_value;
        }

        // Step 3: Build history and count changes
        $currentValues = $initialValues;
        $createdDate = date('d-m-Y', strtotime($user->created_at));
        $dates[] = $createdDate;
        $history[$createdDate] = $currentValues;

        foreach ($changes as $change) {
            $changeDate = date('d-m-Y', strtotime($change->changed_at));
            $field = $fieldMap[$change->field_name] ?? $change->field_name;

            if (!in_array($changeDate, $dates)) {
                $dates[] = $changeDate;
            }

            // Only count when field actually changes value
//            if ($currentValues[$field] !== $change->new_value) {
//                $changeCounts[$field] = ($changeCounts[$field] ?? 0) + 1;
//            }

            $currentValues[$field] = $change->new_value;
            $history[$changeDate] = $currentValues;
        }

        // Keep only the last 5 dates
//        $dates = array_slice($dates, -5, 5, true);

        return view('admin.triumph_portal', [
            'users' => $users,
            'selectedUser' => $user,
            'dates' => $dates,
            'history' => $history,
//            'changeCounts' => $changeCounts,
        ]);
    }


}
