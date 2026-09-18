<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\Helpers\DropdownController;
use App\Http\Controllers\WebController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;


class MyProfileController extends Controller
{

    protected  $webController;

    public function __construct(WebController $webController)
    {
        $this->webController = $webController;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $locationData = DropdownController::getCombinedLocationData($userAndUserDetails);
        $dropdownData = DropdownController::getDropdownData(
            $userAndUserDetails->qualification ?? null,
            $userAndUserDetails->education ?? null,
            $userAndUserDetails->occupation_type ?? null,
            $userAndUserDetails->occupation ?? null,
            $userAndUserDetails->caste ?? null,
            $userAndUserDetails->sub_caste ?? null
        );

        // Fetch enabled tables and their data
        $EnabledTables = DataSharedController::fetchEnabledTables();

        $metaTags = DataSharedController::MetaData('profile');
        $db = DataSharedController::getDatabases();
        $profileCompletion = $this->profileCompletionStatus($userId);

        return view('web.my-profile', array_merge([
            'userAndUserDetails' => $userAndUserDetails,
            'metaTags' => $metaTags,
            'db' => $db,
            'locationData' => $locationData,
            'dropdownData' => $dropdownData,
            'profileCompletion' => $profileCompletion,
        ], $EnabledTables));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = DB::table('users')->where('id', $id)->first();
        $currentDetails = DB::table('user_details')->where('user_id', $id)->first();
        $updateData = [];

        // 1. Deactivate Account
        if ($request->filled('status') || $request->filled('reason')) {
            $request->validate([
                'status' => 'required',
                'reason' => 'required',
            ]);
            DB::table('users')->where('id', $id)->update([
                'status'        => $request->input('status'),
                'reason'        => $request->input('reason'),
                'other_reason'  => $request->input('other_reason'),
            ]);
            Auth::logout();
            return redirect()->route('login')->with('status', 'Account Deactivated Successfully!');
        }

        // 2. Enforce Update Limit
        $allowedCount = DB::table('profile_update_settings')->value('count') ?? 5;
        if ($user->profile_update_count >= $allowedCount) {
            return back()->with('error', 'You have reached the maximum allowed profile updates. Please contact support.');
        }

        // 3. Update Name / Password
        if ($request->hasAny(['name', 'password'])) {
            $rules = [];
            $messages = [];

            if ($request->filled('name')) {
                $rules['name'] = 'string|max:255';
            }

            if ($request->filled('password')) {
                $rules['password'] = [
                    'required', 'string', 'min:6', 'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/'
                ];
                $messages['password.regex'] = 'Password must be at least 6 characters and include both letters and numbers';
            }

            Validator::make($request->all(), $rules, $messages)->validate();

            if ($request->filled('name') && $user->name !== $request->name) {
                $updateData['name'] = ucfirst($request->name);
                $this->logChange($id, 'name', $user->name, $updateData['name']);
            }

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            DB::table('users')->where('id', $id)->update($updateData);
            $this->incrementUpdateCount($id);
            return redirect('my-profile')->with('profile', 'Profile Updated Successfully');
        }

        // 4. Update Personal Information
        if ($request->hasAny([
            'dob', 'birth_time', 'birth_country', 'birth_state', 'birth_city', 'mother_tongue', 'marital_status',
            'ethnicity', 'nationality', 'religion', 'caste', 'sub_caste', 'physical_status',
            'skin_tone', 'height', 'weight', 'body_type', 'eating_habit', 'drinking_habit',
            'smoking_habit', 'profile_image',
        ])) {
            $rules = [
                'dob' => 'required',
                'birth_country' => 'required',
                'birth_state' => 'required',
                'birth_city' => 'required',
                'mother_tongue' => 'required',
                'marital_status' => 'required',
                'religion' => 'required',
                'caste' => 'required',
                'sub_caste' => 'required',
                'height' => 'required',
                'weight' => 'required',
                'physical_status' => 'required',
                'skin_tone' => 'required',
                'body_type' => 'required',
                'eating_habit' => 'required',
                'drinking_habit' => 'required',
                'smoking_habit' => 'required',
            ];

            $hasHour = $request->filled('hour') || $request->filled('birth_hour');
            $hasAmpm = $request->filled('ampm') || $request->filled('birth_ampm');
            if (!$request->filled('birth_time') && (!$hasHour || !$hasAmpm)) {
                $rules['hour'] = 'required';
                $rules['ampm'] = 'required';
            }

            $request->validate($rules);

            // Handle Profile Image
            $updateData['profile_image'] = $this->handleImageUpdate(
                $request->input('profile_image'),
                $currentDetails->profile_image,
                'Profile Image'
            );

            // Birth Time Format
            $hour = $request->input('hour') ?? $request->input('birth_hour');
            $minute = $request->input('minute') ?? $request->input('birth_minute') ?? '00';
            $ampm = $request->input('ampm') ?? $request->input('birth_ampm');

            if (!empty($hour) && !empty($ampm)) {
                $birth_time = "{$hour}:{$minute} {$ampm}";
                $formattedTime = date('H:i:s', strtotime($birth_time));
                $this->checkAndLog($id, 'birth_time', $currentDetails->birth_time, $formattedTime, $updateData);
            } elseif ($request->filled('birth_time')) {
                $formattedTime = date('H:i:s', strtotime($request->input('birth_time')));
                $this->checkAndLog($id, 'birth_time', $currentDetails->birth_time, $formattedTime, $updateData);
            }

            // Other Fields
            $fields = [
                'dob', 'birth_country', 'birth_state', 'birth_city', 'mother_tongue', 'marital_status', 'skin_tone', 'height',
                'weight', 'body_type', 'physical_status', 'eating_habit', 'drinking_habit', 'smoking_habit',
                'religion', 'caste', 'sub_caste', 'ethnicity', 'nationality'
            ];

            foreach ($fields as $field) {
                $this->checkAndLog($id, $field, $currentDetails->$field ?? null, $request->$field, $updateData);
            }

            DB::table('user_details')->where('user_id', $id)->update($updateData);
            $this->incrementUpdateCount($id);
            return redirect()->route('my-profile.index')->with('personal', 'Personal Info Updated Successfully');
        }

        // 5. Education Info
        if ($request->hasAny(['education', 'occupation', 'employed_in', 'monthly_income', 'work_country', 'visa_status'])) {
            $request->validate([
                'education' => 'required',
                'occupation' => 'required',
                'employed_in' => 'required',
                'monthly_income' => 'required',
            ]);

            $fields = ['education', 'employed_in', 'occupation', 'monthly_income', 'work_country', 'visa_status'];
            foreach ($fields as $field) {
                $val = $request->$field;
                if ($field === 'education' && is_array($val)) {
                    $val = implode(', ', array_filter($val));
                }
                $this->checkAndLog($id, $field, $currentDetails->$field ?? null, $val, $updateData);
            }

            DB::table('user_details')->where('user_id', $id)->update($updateData);
            $this->incrementUpdateCount($id);
            return redirect('my-profile')->with('education', 'Education Info Updated Successfully');
        }

        // 6. Family Info
        if ($request->hasAny(['father_name','father_profession','mother_name','mother_profession','family_type','family_status','family_values',
            'no_of_brother','elder_brother','younger_brother','elder_married_brother','younger_married_brother',
            'no_of_sister','elder_sister','younger_sister','elder_married_sister','younger_married_sister',
            'property_details','property_info'])) {

            $fields = ['father_name','father_profession','mother_name','mother_profession','family_type','family_status','family_values',
                'no_of_brother','elder_brother','younger_brother','elder_married_brother','younger_married_brother',
                'no_of_sister','elder_sister','younger_sister','elder_married_sister','younger_married_sister',
                'property_details','property_info'];

            foreach ($fields as $field) {
                $value = $field === 'property_details'
                    ? DataController::formatPropertyDetails($request->input($field))
                    : $request->input($field);
                $this->checkAndLog($id, $field, $currentDetails->$field ?? null, $value, $updateData);
            }

            if (!empty($updateData)) {
                DB::table('user_details')->where('user_id', $id)->update($updateData);
                $this->incrementUpdateCount($id);
            }

            return redirect('my-profile')->with('family', 'Family Info Updated Successfully');
        }

        // 7. Horoscope Info
        if ($request->hasAny(['rashi', 'nakshatra', 'gothram', 'dosham', 'horoscope_image'])) {
            $request->validate([
                'rashi' => 'required',
                'nakshatra' => 'required',
                'dosham' => 'required',
            ]);

            $fields = ['rashi', 'nakshatra', 'gothram', 'dosham'];
            foreach ($fields as $field) {
                $this->checkAndLog($id, $field, $currentDetails->$field ?? null, $request->$field, $updateData);
            }

            $updateData['horoscope_image'] = $this->handleImageUpdate(
                $request->input('horoscope_image'),
                $currentDetails->horoscope_image,
                'Horoscope Image'
            );

            DB::table('user_details')->where('user_id', $id)->update($updateData);
            $this->incrementUpdateCount($id);
            return redirect('my-profile')->with('horoscope', 'Horoscope Info Updated Successfully');
        }

        // 8. Address Info
        if ($request->hasAny(['country', 'state', 'city', 'address', 'pin_code'])) {
            $request->validate([
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'pin_code' => 'required',
                'address' => 'required',
            ]);

            foreach (['country', 'state', 'city', 'address', 'pin_code'] as $field) {
                $this->checkAndLog($id, $field, $currentDetails->$field ?? null, $request->$field, $updateData);
            }

            DB::table('user_details')->where('user_id', $id)->update($updateData);
            $this->incrementUpdateCount($id);
            return redirect('my-profile')->with('address', 'Address Updated Successfully');
        }

        return redirect('my-profile')->with('success', 'No Updates');
    }

    protected function logChange($userId, $field, $oldValue, $newValue): void
    {
        DB::table('triumph_portal')->insert([
            'user_id'    => $userId,
            'field_name' => $field,
            'old_value'  => $oldValue,
            'new_value'  => $newValue,
        ]);
    }

    protected function checkAndLog($userId, $field, $oldValue, $newValue, &$updateData): void
    {
        if ($newValue !== null && $newValue != $oldValue) {
            $updateData[$field] = $newValue;
            $this->logChange($userId, $field, $oldValue, $newValue);
        }
    }

    protected function incrementUpdateCount($userId): void
    {
        DB::table('users')->where('id', $userId)->increment('profile_update_count');
    }

    protected function handleImageUpdate($base64, $currentImage, $folder): string
    {
        if (!$base64) return $currentImage;

        // Delete old image
        if ($currentImage) {
            $path = public_path("$folder/") . $currentImage;
            if (file_exists($path)) unlink($path);
        }

        $image_parts = explode(";base64,", $base64);
        $image_data = base64_decode($image_parts[1]);
        $filename = date('dmY_His') . '.jpg';
        file_put_contents(public_path("$folder/") . $filename, $image_data);

        return $filename;
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function  Gallery(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $galleryImages = DB::table('gallery')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();

        $db = DataSharedController::getDatabases();

        return view('web.gallery', compact(
            'userAndUserDetails', 'galleryImages', 'db',
        ));
    }

    public function GalleryStore(Request $request): RedirectResponse
    {
        $request->validate([
            'gallery_images' => 'required|array|min:1',
            'gallery_images.*' => 'required',
        ]);

        try {
            if ($request->hasFile('gallery_images')) {
                $uploadedFiles = DataSharedController::uploadMultipleImages($request->file('gallery_images'), 'GalleryImage');
                foreach ($uploadedFiles as $file) {
                    DB::table('gallery')->insert([
                        'user_id' => Auth::id(),
                        'image' => $file,
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Images uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while uploading images. Please try again.');
        }
    }

    public function GalleryDelete($id): JsonResponse
    {
        try {
            $image = DB::table('gallery')->where('id', $id)->first();

            if ($image) {
                $filePath = public_path('GalleryImage/' . $image->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                DB::table('gallery')->where('id', $id)->delete();
                return response()->json(['success' => true]);
            }
            return response()->json(['error' => 'Image not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function profileCompletionStatus($userId): int
    {
        $user = DB::table('users')->where('id', $userId)->first();
        $userDetails = DB::table('user_details')->where('user_id', $userId)->first();

        if (!$user || !$userDetails) {
            return 0; // If no user details found, return 0% completion
        }

        // Fields to be considered in the profile completion
        $userFields = [
            'name', 'email', 'country_code','mobile',
        ];

        $userDetailsFields = [
            'profile_for', 'gender', 'dob', 'birth_time', 'birth_country',
            'mother_tongue', 'marital_status', 'skin_tone', 'height', 'body_type', 'physical_status',
            'eating_habit', 'drinking_habit', 'smoking_habit', 'religion', 'caste',
            'education','employed_in', 'occupation', 'monthly_income', 'profile_image',
            'father_profession', 'mother_profession', 'family_type', 'family_status', 'family_values',
            'elder_brother', 'younger_brother', 'elder_married_brother', 'younger_married_brother', 'elder_sister', 'younger_sister', 'elder_married_sister', 'younger_married_sister',
            'property_details', 'rashi', 'gothram', 'dosham', 'horoscope_image',
            'country', 'state', 'city', 'work_country', 'visa_status', 'ethnicity', 'nationality',
        ];

        // Merge all required fields
        $allFields = array_merge($userFields, $userDetailsFields);

        // Count total fields
        $totalFields = count($allFields);
        $filledFields = 0;

        // Count filled fields in users table
        foreach ($userFields as $field) {
            if (!empty($user->$field)) {
                $filledFields++;
            }
        }

        // Count filled fields in user_details table
        foreach ($userDetailsFields as $field) {
            if (!empty($userDetails->$field)) {
                $filledFields++;
            }
        }

        // Calculate profile completion percentage
        $completionPercentage = ($filledFields / $totalFields) * 100;
        return round($completionPercentage);
    }

    public function downloadProfile(): Response|View
    {
        $user = Auth::user();
        $userDetail = DB::table('user_details')->where('user_id', $user->id)->first();
        $data = compact('user', 'userDetail');
//        return view('web.download_profile', $data);
        $pdf = Pdf::loadView('web.download_profile', $data)->setPaper('A4', 'portrait');
        return $pdf->download("{$user->name}.pdf");
    }
}
