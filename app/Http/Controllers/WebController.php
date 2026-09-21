<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiHelperController;
use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Models\User;
use App\Notifications\NewProfileNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Traits\SMSTrait;
use App\Traits\Msg91;
use Illuminate\Support\Facades\Log;

class WebController extends Controller
{

    use SMSTrait;
    use Msg91;

    protected FCMController $fcmController;
    public function __construct(FCMController $fcmController)
    {
        $this->fcmController = $fcmController;
    }

    public function index(): View|RedirectResponse
    {
        $userAndUserDetails = '';

        if (Auth::check()) {
            $userId = Auth::id();
            $userAndUserDetails = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('users.id', '=', $userId)
                ->select('users.*', 'user_details.*','user_details.gender')
                ->first();
            if (!$userAndUserDetails || !$userAndUserDetails->gender) {
                return redirect()->route('registerStep1');
            }
        }

        $highlightedProfiles = DB::table('highlighted_profiles')
            ->join('users', 'users.id', '=', 'highlighted_profiles.user_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->select('users.*', 'user_details.*')
            ->get();

        $metaTags = DataSharedController::MetaData('home');
        $db = DataSharedController::getDatabases();
        $customizeDB = DataSharedController::getCustomizeDatabases();
        return view('welcome', compact('db', 'customizeDB', 'highlightedProfiles', 'userAndUserDetails', 'metaTags'));
    }

    public function registerStep1(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $userAndUserDetails = DataController::getUserDetails($user->id);
//        if (!$user || is_null($user->otp_verified_at)) {
//            return redirect()->route('verify-register-otp', ['mobile' => $user->mobile])
//                ->with('error', 'Please verify your OTP before proceeding.');
//        }

        if (!$user || is_null($user->email_verified_at)) {
            return redirect()->route('verifyRegisterEmailPage', ['email' => $user->email])
                ->with('error', 'Please verify your OTP before proceeding.');
        }

        $EnabledTables = DataSharedController::fetchEnabledTables();
        $data['user_id'] = $user->id;
        $db = DataSharedController::getDatabases();

        return view('web.register-step1', array_merge([
            'db' => $db, 'userAndUserDetails' => $userAndUserDetails,
        ], $data, $EnabledTables));
    }


    public function storeRegisterStep1(Request $request): RedirectResponse
    {
        $rules = [
            'dob'             => [
                'required', 'date',
                function ($attribute, $value, $fail) use ($request) {
                    $age = Carbon::parse($value)->age;

                    if ($request->gender === 'Female' && $age < 18) {
                        $fail('Female candidates must be at least 18 years old.');
                    }

                    if ($request->gender === 'Male' && $age < 21) {
                        $fail('Male candidates must be at least 21 years old.');
                    }
                },
            ],
            'birth_country'   => 'required',
            'birth_state'     => 'required',
            'birth_city'      => 'required',
            'mother_tongue'   => 'required',
            'marital_status'  => 'required',
            'religion'        => 'required',
            'caste'           => 'required',
            'sub_caste'       => 'required',
            'profile_image'   => 'nullable|string',
        ];

        // Check birth time is provided (either via hour/ampm or birth_time)
        $hasHour = $request->filled('hour') || $request->filled('birth_hour');
        $hasAmpm = $request->filled('ampm') || $request->filled('birth_ampm');
        if (!$request->filled('birth_time') && (!$hasHour || !$hasAmpm)) {
            $rules['birth_time'] = 'required';
            $rules['hour'] = 'required';
            $rules['ampm'] = 'required';
        }

        $request->validate($rules);

        // Handle base64 image
        $profileImage = null;
        if ($request->filled('profile_image')) {
            $image_parts = explode(";base64,", $request->input('profile_image'));

            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $profileImage = date('dmY_His') . '.jpg';

                $directory = public_path('Profile Image');
                $file_path = $directory . '/' . $profileImage;
                file_put_contents($file_path, $image_base64);
            }
        }

        // Convert birth time to a 24-hour format
        $birthTime = null;
        $hour = $request->input('hour') ?? $request->input('birth_hour');
        $minute = $request->input('minute') ?? $request->input('birth_minute') ?? '00';
        $ampm = $request->input('ampm') ?? $request->input('birth_ampm');

        if (!empty($hour) && !empty($ampm)) {
            $birthTime = date('H:i:s', strtotime("{$hour}:{$minute} {$ampm}"));
        } elseif ($request->filled('birth_time')) {
            $birthTime = date('H:i:s', strtotime($request->input('birth_time')));
        }
        $userId = $request->input('user_id');
        // Save personal details
        $personalDetails = [
            'user_id' => $userId,
            'dob' => $request->input('dob'),
            'birth_time' => $birthTime,
            'birth_country' => $request->input('birth_country'),
            'birth_state' => $request->input('birth_state'),
            'birth_city' => $request->input('birth_city'),
            'mother_tongue' => $request->input('mother_tongue'),
            'marital_status' => $request->input('marital_status'),
            'ethnicity' => $request->input('ethnicity'),
            'nationality' => $request->input('nationality'),
            'religion' => $request->input('religion'),
            'caste' => $request->input('caste'),
            'sub_caste' => $request->input('sub_caste'),
            'profile_image' => $profileImage,
        ];

        DB::table('user_details')->updateOrInsert(['user_id' => $userId],
            $personalDetails
        );
        DB::table('users')->where('id', $userId)
            ->update(['register_step' => 1]);

        return redirect()->route('registerStep2')->with('success', 'Personal Details Saved Successfully');
    }

    public function registerStep2(Request $request): View
    {
        $userID = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userID);
        $data['user_id'] = $userID;
        $db = DataSharedController::getDatabases();
        return view('web.register-step2', array_merge(['db' => $db, 'userAndUserDetails' => $userAndUserDetails], $data));
    }

    public function storeRegisterStep2(Request $request): RedirectResponse
    {
        $rules = [
            'physical_status'  => 'required',
            'skin_tone'        => 'required',
            'body_type'        => 'required',
            'height'           => 'required',
            'weight'           => 'required',
        ];

        $request->validate($rules);

        $userId = $request->input('user_id');
        $physicalDetails = [
            'user_id' => $userId,
            'physical_status' => $request->input('physical_status'),
            'skin_tone' => $request->input('skin_tone'),
            'height' => $request->input('height'),
            'weight' => $request->input('weight'),
            'body_type' => $request->input('body_type'),
        ];

        DB::table('user_details')->updateOrInsert(['user_id' => $userId],
            $physicalDetails
        );
        DB::table('users')->where('id', $userId)
            ->update(['register_step' => 2]);

        return redirect()->route('registerStep3')->with('success', 'Physical Details Saved Successfully');
    }

    public function registerStep3(Request $request): View
    {
        $userID = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userID);
        $data['user_id'] = $userID;
        $db = DataSharedController::getDatabases();
        return view('web.register-step3', array_merge(['db' => $db, 'userAndUserDetails' => $userAndUserDetails], $data));
    }

    public function storeRegisterStep3(Request $request): RedirectResponse
    {
        $rules = [
            'eating_habit'    => 'required',
            'drinking_habit'  => 'required',
            'smoking_habit'   => 'required',
        ];
        $request->validate($rules);

        $userId = $request->input('user_id');
        $habitualDetails = [
            'user_id' => $userId,
            'eating_habit' => $request->input('eating_habit'),
            'drinking_habit' => $request->input('drinking_habit'),
            'smoking_habit' => $request->input('smoking_habit'),
        ];

        DB::table('user_details')->updateOrInsert(['user_id' => $userId],
            $habitualDetails
        );
        DB::table('users')->where('id', $userId)
            ->update(['register_step' => 3]);

        return redirect()->route('registerStep4')->with('success', 'Habitual Details Saved Successfully');
    }


    public function registerStep4(Request $request): View
    {
        $user_id = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($user_id);
        $data['user_id'] = $user_id;
        $db = DataSharedController::getDatabases();
        return view('web.register-step4', array_merge([
            'db' => $db, 'userAndUserDetails' => $userAndUserDetails,
        ], $data));
    }

    public function storeRegisterStep4(Request $request): RedirectResponse
    {
        $request->validate([
            'education' => 'required',
            'employed_in' => 'required',
            'occupation' => 'required',
            'monthly_income' => 'required',
            'work_country' => 'nullable',
            'visa_status' => 'nullable',
        ]);

        $userId = $request->input('user_id');
        $education = $request->input('education');
        if (is_array($education)) {
            $education = implode(', ', array_filter($education));
        }

        $professionalDetails = [
            'user_id' => $userId,
            'qualification' => $request->input('qualification'),
            'education' => $education,
            'employed_in' => $request->input('employed_in'),
            'occupation_type' => $request->input('occupation_type'),
            'occupation' => $request->input('occupation'),
            'monthly_income' => $request->input('monthly_income'),
            'work_country' => $request->input('work_country'),
            'visa_status' => $request->input('visa_status'),
        ];

        DB::table('user_details')->updateOrInsert(['user_id' => $userId],
            $professionalDetails
        );
        DB::table('users')->where('id', $userId)->update(['register_step' => 4]);

        return redirect()->route('registerStep5')->with('success', 'Professional Details Saved Successfully');
    }

    public function registerStep5(Request $request): View
    {
        $user_id = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($user_id);
        $db = DataSharedController::getDatabases();
        return view('web.register-step5', compact('user_id', 'db', 'userAndUserDetails'));
    }

    public function storeRegisterStep5(Request $request): RedirectResponse
    {
        $rules = [
            'father_name' => 'required',
            'mother_name' => 'required',
        ];

        $request->validate($rules);

        $propertyDetailsString = DataController::formatPropertyDetails($request->input('property_details'));

        $userId = $request->input('user_id');
        $familyDetails = [
            'user_id' => $userId,
            'father_name' => $request->input('father_name'),
            'father_profession' => $request->input('father_profession'),
            'mother_name' => $request->input('mother_name'),
            'mother_profession' => $request->input('mother_profession'),
            'family_type' => $request->input('family_type'),
            'family_status' => $request->input('family_status'),
            'family_values' => $request->input('family_values'),
            'no_of_brother' => $request->input('no_of_brother'),
            'elder_brother' => $request->input('elder_brother'),
            'younger_brother' => $request->input('younger_brother'),
            'elder_married_brother' => $request->input('elder_married_brother'),
            'younger_married_brother' => $request->input('younger_married_brother'),
            'no_of_sister' => $request->input('no_of_sister'),
            'elder_sister' => $request->input('elder_sister'),
            'younger_sister' => $request->input('younger_sister'),
            'elder_married_sister' => $request->input('elder_married_sister'),
            'younger_married_sister' => $request->input('younger_married_sister'),
            'property_details' => $propertyDetailsString,
            'property_info' => $request->input('property_info'),
        ];
        DB::table('user_details')->updateOrInsert(['user_id' => $userId],
            $familyDetails
        );
        DB::table('users')->where('id', $userId)->update(['register_step' => 5]);
        return redirect()->route('registerStep6')->with('success', 'Family Details Saved Successfully');
    }

    public function registerStep6(Request $request): View
    {
        $user_id = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($user_id);
        $data['user_id'] = $user_id;
        $db = DataSharedController::getDatabases();
        return view('web.register-step6', array_merge(['db' => $db, 'userAndUserDetails' => $userAndUserDetails], $data));
    }

    public function storeRegisterStep6(Request $request): RedirectResponse
    {
        $request->validate([
            'rashi' => 'required',
            'nakshatra' => 'required',
            'gothram' => 'nullable',
            'dosham' => 'required',
            'horoscope_image' => 'nullable|string'
        ]);

        // Handle base64 image
        $horoscopeImageName = null;
        if ($request->filled('horoscope_image')) {
            $image_parts = explode(";base64,", $request->input('horoscope_image'));

            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $horoscopeImageName = date('dmY_His') . '.jpg';

                $directory = public_path('Horoscope Image');
                $file_path = $directory . '/' . $horoscopeImageName;
                file_put_contents($file_path, $image_base64);
            }
        }
//        $image_parts = explode(";base64,", $request->input('horoscope_image'));
//        $image_base64 = base64_decode($image_parts[1]);
//        $horoscopeImageName = date('dmY_His') . '.jpg';
//        $file_path = public_path('Horoscope Image') . '/' . $horoscopeImageName;
//        file_put_contents($file_path, $image_base64);

        $userId = $request->input('user_id');
        $horoscopeDetails = [
            'user_id' => $userId,
            'rashi' => $request->input('rashi'),
            'nakshatra' => $request->input('nakshatra'),
            'gothram' => $request->input('gothram'),
            'dosham' => $request->input('dosham'),
            'horoscope_image' => $horoscopeImageName
        ];

        DB::table('user_details')->updateOrInsert(['user_id' => $userId],
            $horoscopeDetails
        );
        DB::table('users')->where('id', $userId)->update(['register_step' => 6]);

        return redirect()->route('registerStep7')->with('success', 'Horoscope Details Saved Successfully');
    }

    public function registerStep7(): View
    {
        $user_id = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($user_id);
        $db = DataSharedController::getDatabases();
        return view('web.register-step7', compact('user_id', 'db', 'userAndUserDetails'));
    }

    public function storeRegisterStep7(Request $request): RedirectResponse
    {
        $request->validate([
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pin_code' => 'required',
            'address' => 'required',
        ]);

        $userId = $request->input('user_id');
        $addressDetails = [
            'user_id' => $userId,
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'pin_code' => $request->input('pin_code'),
        ];

        DB::table('user_details')->updateOrInsert(['user_id' => $userId], $addressDetails);
        DB::table('users')->where('id', $userId)->update(['status' => 'pending', 'register_step' => 7]);

        Auth::logout();
        return redirect()->route('login')->with('message', 'Thank you for registering with Bala Matrimony Bureau. Your account is currently under review. You will receive a confirmation once it is activated.');
    }



    public function getRecentUserDetails(): ?array
    {
        $userId = Auth::id();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        // If no user details or gender not defined, return an empty array
        if (!$userAndUserDetails || !$userAndUserDetails->gender) {
            return [];
        }

        // Fetch user's package details
        $latestReceiptSubquery = DB::table('receipts')
            ->select('user_id', DB::raw('MAX(id) as latest_id'))
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->groupBy('user_id');

        // Determine the opposite gender based on the user's gender
        $userGender = strtolower($userAndUserDetails->gender);
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';

        // Fetch recent user details of the opposite gender
        $recentUserDetails = DB::table('user_details')
            ->join('users', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('settings', 'user_details.user_id', '=', 'settings.user_id')
            ->leftJoinSub($latestReceiptSubquery, 'latest_receipts', function ($join) {
                $join->on('user_details.user_id', '=', 'latest_receipts.user_id');
            })
            ->leftJoin('receipts', 'receipts.id', '=', 'latest_receipts.latest_id')
            ->where('user_details.gender', $oppositeGender)
            ->orderBy('user_details.created_at', 'desc')
            ->select(
                'user_details.*',
                'users.*',
                'settings.profile_picture_visibility',
                'receipts.package as user_package'
            )
            ->limit(10)
            ->get();

        return [
            'userAndUserDetails' => $userAndUserDetails,
            'recentUserDetails' => $recentUserDetails,
            'recentUserDetailsCount' => count($recentUserDetails)
        ];
    }


    public function dashboard(): View|RedirectResponse
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);
        if (!$userAndUserDetails || !$userAndUserDetails->gender) {
            return redirect('/');
        }

        $userPackage = DataController::getUserPackageDetails($userId);
        $isPackageValid = $userPackage['package'];
        $userPackageValue = $userPackage['is_active'];

        $oppositeGender = DataController::getOppositeGender($userAndUserDetails->gender);

        $recentUserDetails = DataController::getRecentProfiles($oppositeGender, $userPackageValue);
        $recentUserDetailsCount = count($recentUserDetails);

        $relatedProfiles = DataController::getPreferenceBasedProfiles($userId, $oppositeGender, $userPackageValue);
        $relatedProfilesCount = count($relatedProfiles);

        $profileViewedByMeData = DataController::getProfileViewsWithPrivacy($userId, 'viewed_by_me', $userPackageValue);
        $profileViewedByMe = $profileViewedByMeData['profiles'];
        $profileViewedByMeCount = $profileViewedByMeData['count'];

        $viewedMyProfileData = DataController::getProfileViewsWithPrivacy($userId, 'viewed_my_profile', $userPackageValue);
        $viewedMyProfile = $viewedMyProfileData['profiles'];
        $viewedMyProfileCount = $viewedMyProfileData['count'];

        $contactViewedByMe = DataController::getContactViews($userId, 'viewed_by_me', $userPackageValue);
        $viewedMyContact = DataController::getContactViews($userId, 'viewed_my_contact', $userPackageValue);

        $metaTags = DataSharedController::MetaData('dashboard');
        $db = DataSharedController::getDatabases();

        return view('dashboard', compact('userAndUserDetails', 'recentUserDetails',
            'relatedProfiles', 'userPackage', 'isPackageValid', 'viewedMyProfile',
            'profileViewedByMe', 'viewedMyContact', 'contactViewedByMe', 'metaTags','userPackageValue',
            'recentUserDetailsCount', 'viewedMyProfileCount' , 'relatedProfilesCount', 'db', 'profileViewedByMeCount'
        ));
    }

    public function package404(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        $db = DataSharedController::getDatabases();
        return view('web.package-404', compact('userAndUserDetails', 'db'));
    }


    public function termsAndCondition(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        $db = DataSharedController::getDatabases();
        return view('web.terms-and-conditions', compact('db', 'userAndUserDetails'));
    }

    public function privacyPolicy(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        $db = DataSharedController::getDatabases();
        return view('web.privacy-policy', compact('db', 'userAndUserDetails'));
    }

    public function refundPolicy(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        $db = DataSharedController::getDatabases();
        return view('web.refund-policy', compact('db', 'userAndUserDetails'));
    }

    public function aboutUs(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        $db = DataSharedController::getDatabases();
        return view('web.about-us', compact('db','userAndUserDetails'));
    }

    public function contactUs(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select('users.*', 'user_details.*')
            ->first();

        $db = DataSharedController::getDatabases();
        return view('web.contact-us', compact('userAndUserDetails', 'db'));
    }

    public function storeContactUs(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => 'required',
            'attachment' => 'nullable|max:10240'
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);
        $responseData = $response->json();

        if (!$responseData['success']) {
            return redirect()->back()->withErrors(['captcha' => 'Captcha validation failed. Please try again.']);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentFile = $request->file('attachment');
            $filenameWithExt = 'A' . date('dmYHis') . '.' . $attachmentFile->getClientOriginalExtension();
            $attachmentFile->move(public_path('Attachment'), $filenameWithExt);
            $attachmentPath = $filenameWithExt;
        }

        DB::table('enquiry')->insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'attachment' => $attachmentPath,
        ]);

        return redirect()->back()->with('success', 'Your enquiry has been sent.');
    }

    public function Help(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        $db = DataSharedController::getDatabases();
        return view('web.help-guide', compact('db', 'userAndUserDetails'));
    }

    public function contacts(): View
    {
        $userId = Auth::id();
        $userAndUserDetails = DataController::getUserDetails($userId);

        // Users whom the logged-in user has viewed
        $viewedContacts = ApiHelperController::getContactUsers('user_id', $userId)->map(function ($profile) {
            $packageDetails = DataController::getUserPackageDetails($profile->id);
            $package = $packageDetails['package'] ?? '';

            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $package);
            $profile->email = ApiHelperController::privacyData($profile->email, $profile->email_visibility, $package);
            $profile->mobile = ApiHelperController::privacyData($profile->mobile, $profile->mobile_number_visibility, $package);
            $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, $package);

            return $profile;
        });

        // Users who viewed the logged-in user
        $viewerContacts = ApiHelperController::getContactUsers('profile_id', $userId)->map(function ($profile) {
            $packageDetails = DataController::getUserPackageDetails($profile->id);
            $package = $packageDetails['package'] ?? '';

            $profile->name = ApiHelperController::privacyData($profile->name, $profile->name_visibility, $package);
            $profile->email = ApiHelperController::privacyData($profile->email, $profile->email_visibility, $package);
            $profile->mobile = ApiHelperController::privacyData($profile->mobile, $profile->mobile_number_visibility, $package);
            $profile->profile_image = ApiHelperController::ImageUrl($profile->profile_image, $profile->profile_picture_visibility, $package);

            return $profile;
        });

        $db = DataSharedController::getDatabases();
        return view('web.contacts', compact('db', 'userAndUserDetails', 'viewedContacts', 'viewerContacts'));
    }

}
