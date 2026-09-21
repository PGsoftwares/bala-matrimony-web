<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Controllers\Helpers\DropdownController;
use App\Models\User;
use App\Traits\MailService;
use App\Traits\Msg91;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class  UserDetailsController extends Controller
{
    use Msg91;
    use MailService;
    protected FCMController $fcmController;
    public function __construct(FCMController $fcmController)
    {
        $this->fcmController = $fcmController;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id): View
    {
        // Retrieve user details by ID
        $user = DB::table('users')->where('id', $id)->first();
        $packageDetails = DataController::getUserPackageDetails($id);
        $receipt = $packageDetails['receipt'];

        if (!$receipt) {
            $receipt = DB::table('receipts')->where('user_id', $id)->orderByDesc('id')->first();
        }

        $userAndUserDetails = DataController::getUserDetails($id);
        if ($userAndUserDetails) {
            $userAndUserDetails->package = $packageDetails['package'] ?: ($receipt->package ?? '');
        }

        $locationData = DropdownController::getCombinedLocationData($userAndUserDetails);
        $dropdownData = DropdownController::getDropdownData(
            $userAndUserDetails->qualification ?? null,
            $userAndUserDetails->education ?? null,
            $userAndUserDetails->occupation_type ?? null,
            $userAndUserDetails->occupation ?? null,
            $userAndUserDetails->caste ?? null,
            $userAndUserDetails->sub_caste ?? null
        );

        $db = DataSharedController::getDatabases();

        $EnabledTables = DataSharedController::fetchEnabledTables();
        $notes = DB::table("notes")->where('user_id', $id)->get();

        return view('admin.user-details',
            array_merge([
                'db' => $db,
                'user' => $user,
                'userAndUserDetails' => $userAndUserDetails,
                'receipt' => $receipt,
                'locationData' => $locationData,
                'dropdownData' => $dropdownData,
                'notes' => $notes,
            ], $EnabledTables)
        );
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
        $userDetail = DB::table('user_details')->where('user_id', $id)->first();
        if (!$userDetail) {
            return redirect('admin/user-list')->with('error', 'User not found');
        }

        $section = $request->input('update_section');

        switch ($section) {
            case 'personal':
                $rules = [
                    'profile_for' => 'required',
                    'gender' => 'required',
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
                    'profile_image' => 'nullable|string',
                ];

                $hasHour = $request->filled('hour') || $request->filled('birth_hour');
                $hasAmpm = $request->filled('ampm') || $request->filled('birth_ampm');
                if (!$request->filled('birth_time') && (!$hasHour || !$hasAmpm)) {
                    $rules['hour'] = 'required';
                    $rules['ampm'] = 'required';
                }

                $request->validate($rules);

                $profileImage = $this->handleBase64Image(
                    $request->input('profile_image'),
                    $userDetail->profile_image,
                    'Profile Image'
                );

                $formattedBirthTime = null;
                $hour = $request->input('hour') ?? $request->input('birth_hour');
                $minute = $request->input('minute') ?? $request->input('birth_minute') ?? '00';
                $ampm = $request->input('ampm') ?? $request->input('birth_ampm');

                if (!empty($hour) && !empty($ampm)) {
                    $formattedBirthTime = date('H:i:s', strtotime("{$hour}:{$minute} {$ampm}"));
                } elseif ($request->filled('birth_time')) {
                    $formattedBirthTime = date('H:i:s', strtotime($request->input('birth_time')));
                }

                $personalInfo = [
                    'profile_for' => $request->input('profile_for'),
                    'gender' => $request->input('gender'),
                    'dob' => $request->input('dob'),
                    'birth_time' => $formattedBirthTime,
                    'birth_country' => $request->input('birth_country'),
                    'birth_state' => $request->input('birth_state'),
                    'birth_city' => $request->input('birth_city'),
                    'ethnicity' => $request->input('ethnicity'),
                    'nationality' => $request->input('nationality'),
                    'mother_tongue' => $request->input('mother_tongue'),
                    'marital_status' => $request->input('marital_status'),
                    'skin_tone' => $request->input('skin_tone'),
                    'height' => $request->input('height'),
                    'weight' => $request->input('weight'),
                    'body_type' => $request->input('body_type'),
                    'physical_status' => $request->input('physical_status'),
                    'eating_habit' => $request->input('eating_habit'),
                    'drinking_habit' => $request->input('drinking_habit'),
                    'smoking_habit' => $request->input('smoking_habit'),
                    'religion' => $request->input('religion'),
                    'caste' => $request->input('caste'),
                    'sub_caste' => $request->input('sub_caste'),
                    'profile_image' => $profileImage,
                ];

                DB::table('user_details')->where('user_id', $id)->update($personalInfo);
                return redirect()->route('user-details.show', $id)->with('success', 'Personal Details updated successfully');

            case 'education':
                $request->validate([
                    'education' => 'required',
                    'employed_in' => 'required',
                    'occupation' => 'required',
                    'monthly_income' => 'required',
                ]);

                $educationData = $request->only([
                    'education', 'employed_in', 'occupation', 'monthly_income', 'work_country', 'visa_status'
                ]);

                if (is_array($educationData['education'] ?? null)) {
                    $educationData['education'] = implode(', ', array_filter($educationData['education']));
                }

                DB::table('user_details')->where('user_id', $id)->update($educationData);

                return redirect()->route('user-details.show', $id)->with('success', 'Education Details Updated Successfully');

            case 'family':
                $request->validate([
                    'father_name' => 'required',
                    'mother_name' => 'required',
                ]);

                $familyDetails = $request->only([
                    'father_name', 'father_profession', 'mother_name', 'mother_profession', 'family_type', 'family_status',
                    'family_values', 'no_of_brother', 'elder_brother', 'younger_brother',
                    'elder_married_brother', 'younger_married_brother',
                    'no_of_sister', 'elder_sister', 'younger_sister',
                    'elder_married_sister', 'younger_married_sister', 'property_info'
                ]);
                $familyDetails['property_details'] = DataController::formatPropertyDetails($request->input('property_details'));

                DB::table('user_details')->where('user_id', $id)->update($familyDetails);
                return redirect()->route('user-details.show', $id)->with('success', 'Family Details Updated Successfully');

            case 'horoscope':
                $request->validate([
                    'rashi' => 'required',
                    'nakshatra' => 'required',
                    'dosham' => 'required',
                    'horoscope_image' => 'nullable|string',
                ]);

                $horoscopeImage = $this->handleBase64Image(
                    $request->input('horoscope_image'),
                    $userDetail->horoscope_image,
                    'Horoscope Image'
                );

                $horoscopeDetails = $request->only([
                    'rashi', 'nakshatra', 'gothram', 'dosham'
                ]);
                $horoscopeDetails['horoscope_image'] = $horoscopeImage;

                DB::table('user_details')->where('user_id', $id)->update($horoscopeDetails);
                return redirect()->route('user-details.show', $id)->with('success', 'Horoscope Details Updated Successfully');

            case 'address':
                $request->validate([
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'pin_code' => 'required',
                    'address' => 'required',
                ]);

                DB::table('user_details')->where('user_id', $id)->update($request->only([
                    'country', 'state', 'city', 'address', 'pin_code'
                ]));
                return redirect()->route('user-details.show', $id)->with('success', 'Address Details Updated Successfully');

            case 'deactivate':
                DB::table('users')->where('id', $id)->update(['status' => $request->input('deactivated')]);
                return redirect()->route('user-details.show', $id)->with('success', 'Account Deactivated Successfully');

            case 'activate':
                DB::table('users')->where('id', $id)->update(['status' => $request->input('active')]);
                $user = DB::table('users')->where('id', $id)->first();
                /* Account activation through Mail  */
                // if ($user) {
                //   $this->accountActivationEmail($user);
                // }

                /* Account activation through SMS */
                // if ($user) {
                //    $mobileNumber = ltrim($user->country_code, '+') . $user->mobile;
                //    $this->sendMsg91Flow($mobileNumber, 'account_activation');
                // }
                return redirect()->route('user-details.show', $id)->with('success', 'Account Activated Successfully');

            case 'account_info':
                $request->validate([
                    'name' => 'nullable|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
                    'email' => 'nullable|email|unique:users,email,' . $id,
                    'mobile' => 'nullable|numeric|digits:10|unique:users,mobile,' . $id,
                    'password' => 'nullable|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@#$%^&+=!]{6,}$/'
                ]);

                $updateFields = [];
                if ($request->filled('name')) $updateFields['name'] = Str::title(strtolower($request->input('name')));
                if ($request->filled('email')) $updateFields['email'] = $request->input('email');
                if ($request->filled('mobile')) $updateFields['mobile'] = $request->input('mobile');
                if ($request->filled('password')) $updateFields['password'] = Hash::make($request->input('password'));

                DB::table('users')->where('id', $id)->update($updateFields);
                return redirect()->route('user-details.show', $id)->with('success', 'Account Information Updated Successfully');

            case 'package':
                $packageName = $request->input('package');
                $now = Carbon::now();

                if (!empty($packageName)) {
                    $package = DB::table('packages')->where('name', $packageName)->first();
                    if ($package) {
                        DB::table('receipts')->where('user_id', $id)->update([
                            'status' => 'closed',
                            'updated_at' => $now
                        ]);

                        $months = (int) ($package->month ?? 0);
                        DB::table('receipts')->insert([
                            'user_id' => $id,
                            'package' => $package->name,
                            'month' => $package->month,
                            'amount' => $package->amount,
                            'no_of_contact' => $package->no_of_contact,
                            'balance' => $package->no_of_contact,
                            'no_of_viewed' => 0,
                            'no_of_chats' => $package->no_of_chats,
                            'viewed_chats' => 0,
                            'balance_chats' => $package->no_of_chats,
                            'no_of_interests' => $package->no_of_interests,
                            'viewed_interests' => 0,
                            'balance_interests' => $package->no_of_interests,
                            'paid_by' => 'admin',
                            'payment_method' => 'admin',
                            'status' => 'paid',
                            'recharge_date' => $now,
                            'expiry_date' => $months > 0 ? $now->copy()->addMonths($months) : null,
                            'order_id' => (string) Str::uuid(),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                        $user = DB::table('users')->where('id', $id)->first();
                        $adminUser = DB::table('users')->where('role', 'admin')->first();
                        if ($adminUser && !empty($adminUser->device_token) && isset($this->fcmController)) {
                            try {
                                $userName = $user ? $user->name : 'User';
                                $this->fcmController->sendFcmNotificationHelper(
                                    $adminUser->device_token,
                                    'Package Upgraded',
                                    "User {$userName} upgraded to {$package->name} package."
                                );
                            } catch (\Exception $e) {
                                // Suppress FCM notification errors
                            }
                        }

                        return redirect()->route('user-details.show', $id)->with('success', 'Package Details Updated Successfully');
                    } else {
                        return redirect()->route('user-details.show', $id)->with('error', 'Selected package not found');
                    }
                } else {
                    // If no package selected, close existing package
                    DB::table('receipts')->where('user_id', $id)->update([
                        'status' => 'closed',
                        'updated_at' => $now
                    ]);
                    return redirect()->route('user-details.show', $id)->with('success', 'Package status updated to inactive');
                }

            case 'note':
                DB::table('notes')->insert([
                    'user_id' => $id,
                    'note' => $request->input('note')
                ]);
                return redirect()->route('user-details.show', $id)->with('success', 'Notes Saved Successfully');

            default:
                return redirect('admin/user-list')->with('success', 'No Updates');
        }
    }

    private function handleBase64Image(?string $base64Image, ?string $oldImageName, string $folder): ?string
    {
        if (!$base64Image) return $oldImageName;

        if (!empty($oldImageName)) {
            $oldImagePath = public_path($folder) . '/' . $oldImageName;
            if (file_exists($oldImagePath)) unlink($oldImagePath);
        }

        $image_parts = explode(";base64,", $base64Image);
        if (count($image_parts) !== 2) return $oldImageName;

        $image_base64 = base64_decode($image_parts[1]);
        $imageName = date('dmY_His') . '.jpg';
        file_put_contents(public_path($folder) . '/' . $imageName, $image_base64);

        return $imageName;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function deleteImage(Request $request): RedirectResponse
    {
        $userDetail = DB::table('user_details')->where('user_id', $request->input('user_id'))->first();

        if (!$userDetail) {
            return back()->with('error', 'User not found.');
        }
        $updateData = [];
        if ($request->input('type') == 'profile') {
            DataSharedController::deleteImage($userDetail->profile_image, 'Profile Image');
            $updateData['profile_image'] = null;
        } elseif ($request->input('type') == 'horoscope') {
            DataSharedController::deleteImage($userDetail->horoscope_image, 'Horoscope Image');
            $updateData['horoscope_image'] = null;
        }
        DB::table('user_details')->where('user_id', $request->input('user_id'))->update($updateData);
        return back()->with('image', 'Image deleted successfully.');
    }

    public function printUser($id): View
    {
        $user = DB::table('users')->where('id', $id)->first();

        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('receipts', function ($join) {
                $join->on('users.id', '=', 'receipts.user_id')
                    ->where('receipts.id', '=', DB::raw("(SELECT MAX(id) FROM receipts WHERE user_id = users.id)"));
            })
            ->where('users.id', $id)
            ->distinct('users.id', $id)
            ->select('users.*', 'user_details.*', 'receipts.package as package')
            ->get();

        $preference = DB::table('set_preferences')->where('user_id', $id)->first();
        $receipt = DB::table('receipts')->where('user_id', $id)->where('status', 'paid')->orderByDesc('id')->first();
        $db = DataSharedController::getDatabases();

        return view('admin.print-user', compact('user', 'userAndUserDetails', 'preference', 'receipt', 'db'));
    }

    public function addPreference($id): View
    {
        $user = DB::table('users')->where('id', $id)->first();
        $userAndUserDetails = DataController::getUserDetails($id);

        $preference = DB::table('set_preferences')
            ->where('user_id', $id)
            ->select('set_preferences.*')
            ->first();

        $locationData = DropdownController::getCombinedLocationData($preference);
        $dropdownData = DropdownController::getDropdownData(
            $preference?->qualification, $preference?->education,
            $preference?->occupation_type, $preference?->occupation,
            $preference?->caste, $preference?->sub_caste
        );

        $db = DataSharedController::getDatabases();

        return view('admin.add-preference', array_merge(
            [
                'userAndUserDetails' => $userAndUserDetails, 'preference' => $preference, 'db' => $db,
                'locationData' => $locationData, 'dropdownData' => $dropdownData, 'user' =>  $user
            ]
        ));
    }

    public function storePreference(Request $request, $id): RedirectResponse
    {
        $userId = $id;
        /*  list every field that can come in as an array  */
        $multiSelectFields = [
            'country', 'state', 'city',
            'mother_tongue', 'marital_status', 'skin_tone', 'body_type',
            'drinking_habit', 'smoking_habit', 'eating_habit', 'physical_status',
            'religion', 'caste', 'sub_caste', 'qualification', 'education',
            'occupation_type', 'occupation', 'employed_in',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham',
            'ethnicity', 'nationality', 'work_country', 'visa_status'
        ];

        /* start the data array with scalar / range fields  */
        $preferenceData = [
            'user_id'            => $userId,
            'min_age'            => $request->input('min_age'),
            'max_age'            => $request->input('max_age'),
            'height_from'        => $request->input('height_from'),
            'height_to'          => $request->input('height_to'),
            'monthly_income_from'=> $request->input('monthly_income_from'),
            'monthly_income_to'  => $request->input('monthly_income_to'),
        ];

        foreach ($multiSelectFields as $field) {
            $value = $request->input($field);
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $preferenceData[$field] = $value;
        }

        $exists = DB::table('set_preferences')->where('user_id', $userId)->exists();
        if ($exists) {
            DB::table('set_preferences')
                ->where('user_id', $userId)
                ->update($preferenceData);
            $message = 'Preferences updated successfully.';
        } else {
            DB::table('set_preferences')->insert($preferenceData);
            $message = 'Preferences added successfully.';
        }
        return redirect()->back()->with('success', $message);
    }


}
