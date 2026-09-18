<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Models\Register;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.register');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $user_id = $request->user_id;
        $db = DataSharedController::getDatabases();

        return view('admin.authentication.register-details', array_merge([
                'user_id' => $user_id,
                'db' => $db
            ])
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'profile_image' => 'nullable|image|max:3072',
            'horoscope_image' => 'nullable|image|max:3072',
            'dob' => 'required',
            'birth_country' => 'required',
            'birth_state' => 'required',
            'birth_city' => 'required',
            'mother_tongue' => 'required',
            'marital_status' => 'required',
            'religion' => 'required',
            'physical_status' => 'required',
            'caste' => 'required',
            'sub_caste' => 'required',
            'skin_tone' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'body_type' => 'required',
            'eating_habit' => 'required',
            'drinking_habit' => 'required',
            'smoking_habit' => 'required',
            'education' => 'required',
            'employed_in' => 'required',
            'occupation' => 'required',
            'monthly_income' => 'required',
            'rashi' => 'required',
            'nakshatra' => 'required',
            'dosham' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pin_code' => 'required',
            'address' => 'required',
        ];

        $hasHour = $request->filled('birth_hour') || $request->filled('hour');
        $hasAmpm = $request->filled('birth_ampm') || $request->filled('ampm');
        if (!$request->filled('birth_time') && (!$hasHour || !$hasAmpm)) {
            $rules['birth_hour'] = 'required';
            $rules['birth_ampm'] = 'required';
        }

        $validatedData = $request->validate($rules);

        // Handle file upload for profile_image
        $profileImageName = null;
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $profileImageName = date('dmYHis') . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->move(public_path('Profile Image'), $profileImageName);
        }

        // Handle file upload for horoscope_image
        $horoscopeImageName = null;
        if ($request->hasFile('horoscope_image')) {
            $horoscopeImage = $request->file('horoscope_image');
            $horoscopeImageName = date('dmYHis') . '.' . $horoscopeImage->getClientOriginalExtension();
            $horoscopeImage->move(public_path('Horoscope Image'), $horoscopeImageName);
        }

        // Convert birth time to 24-hour format
        $birthTime = null;
        $hour = $request->input('birth_hour') ?? $request->input('hour');
        $minute = $request->input('birth_minute') ?? $request->input('minute') ?? '00';
        $ampm = $request->input('birth_ampm') ?? $request->input('ampm');

        if (!empty($hour) && !empty($ampm)) {
            $birthTime = date('H:i:s', strtotime("{$hour}:{$minute} {$ampm}"));
        } elseif ($request->filled('birth_time')) {
            $birthTime = date('H:i:s', strtotime($request->input('birth_time')));
        }

        $user_id = $request->input('user_id');
        $education = $request->input('education');
        if (is_array($education)) {
            $education = implode(', ', array_filter($education));
        }
        $propertyDetails = DataController::formatPropertyDetails($request->input('property_details'));
        $registerDetails = array_merge($request->only([
            'dob', 'birth_country', 'birth_state', 'birth_city', 'mother_tongue', 'marital_status', 'ethnicity', 'nationality',
            'religion', 'caste', 'sub_caste', 'physical_status', 'skin_tone', 'height', 'weight', 'body_type',  'eating_habit',
            'drinking_habit', 'smoking_habit', 'employed_in', 'occupation',
            'monthly_income', 'work_country', 'visa_status', 'father_profession', 'mother_profession', 'family_type',
            'family_status', 'family_values', 'no_of_brother', 'elder_brother', 'younger_brother',
            'elder_married_brother', 'younger_married_brother',
            'no_of_sister', 'elder_sister', 'younger_sister',
            'elder_married_sister', 'younger_married_sister', 'property_info',
            'rashi', 'nakshatra', 'gothram', 'dosham', 'country', 'state', 'city', 'address', 'pin_code',
        ]), [
            'user_id' => $user_id,
            'education' => $education,
            'birth_time' => $birthTime,
            'profile_image' => $profileImageName,
            'horoscope_image' => $horoscopeImageName,
            'property_details' => $propertyDetails,
        ]);

        // Insert or update record
        DB::table('user_details')->updateOrInsert(
            ['user_id' => $user_id,],
            $registerDetails
        );
        DB::table('users')->where('id', $user_id)->update([
            'register_step' => 7,
        ]);
        return redirect()->route('register-details.index')->with('success', 'Profile created successfully');
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
    public function destroy(string $id)
    {
        //
    }
}
