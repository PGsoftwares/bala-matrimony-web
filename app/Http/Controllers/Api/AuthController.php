<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataController;
use App\Mail\PasswordResetOtp;
use App\Mail\SendOtpMail;
use App\Notifications\Api\SendOtpNotification;
use App\Notifications\NewProfileNotification;
use App\Http\Controllers\Api\FCMController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Traits\SMSTrait;
use App\Traits\Msg91;


class AuthController extends Controller
{
    use SMSTrait;
    use Msg91;
    protected FCMController $fcmController;
    public function __construct(FCMController $fcmController)
    {
        $this->fcmController = $fcmController;
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'country_code' => ['required', 'string'],
            'mobile'       => ['required', 'string'],
            'gender'       => ['required', 'string'],
            'profile_for'  => ['required', 'string'],
            'password'     => ['required', 'confirmed', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are required.',
                'errors'  => $validator->messages()
            ], 400);
        }

        $countryCode = ltrim($request->input('country_code'), '+');
        $localMobile = $request->input('mobile');
        $email       = $request->input('email');
        $otp         = rand(1000, 9999);
        $expiryMinutes = 3;

        // Check if user already exists
        $existingUser = DB::table('users')
            ->where('email', $email)
            ->orWhere(function ($q) use ($localMobile, $countryCode) {
                $q->where('mobile', $localMobile)
                    ->where('country_code', $countryCode);
            })
            ->first();

        if ($existingUser) {
            if ($existingUser->email_verified_at) {
                return response()->json([
                    'message' => 'User already registered and verified.',
                    'user'    => [
                        'id'            => $existingUser->id,
                        'name'          => $existingUser->name,
                        'email'         => $existingUser->email,
                        'mobile'        => $existingUser->mobile,
                        'register_step' => $existingUser->register_step,
                    ]
                ], 200);
            }

            // Resend OTP via email
            DB::table('users')
                ->where('id', $existingUser->id)
                ->update([
                    'email_otp'            => $otp,
                    'email_expires_at' => now()->addMinutes($expiryMinutes),
                ]);

            try {
                Mail::html("
                        <p>Dear {$existingUser->name},</p>
                        <p>Your OTP is <strong>{$otp}</strong>.</p>
                        <p>This code will expire in {$expiryMinutes} minutes.</p>
                        <p>If you did not request this, please ignore this email.</p>
                    ", function ($message) use ($email) {
                    $message->to($email)->subject('Your OTP Code');
                });

            } catch (\Exception $e) {
                Log::error('Failed to send OTP via email: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Failed to resend OTP via email.',
                ], 500);
            }

            return response()->json([
                'message' => 'User already registered but not verified. OTP resent via email.',
                'user'    => [
                    'id'            => $existingUser->id,
                    'name'          => $existingUser->name,
                    'email'         => $existingUser->email,
                    'mobile'        => $existingUser->mobile,
                    'register_step' => $existingUser->register_step,
                ],
            ], 200);
        }

        // Create new user
        $userId = DB::table('users')->insertGetId([
            'name'           => ucfirst($request->input('name')),
            'email'          => $email,
            'country_code'   => $countryCode,
            'mobile'         => $localMobile,
            'password'       => Hash::make($request->input('password')),
            'device_token'   => $request->input('device_token'),
            'email_otp'      => $otp,
            'email_expires_at' => now()->addMinutes($expiryMinutes),
            'register_step'  => 0,
        ]);

        DB::table('user_details')->insert([
            'user_id'     => $userId,
            'gender'      => $request->input('gender'),
            'profile_for' => $request->input('profile_for'),
        ]);

        try {
            Mail::html("
                    <p>Dear {$request->input('name')},</p>
                    <p>Your OTP is <strong>{$otp}</strong>.</p>
                    <p>This code will expire in {$expiryMinutes} minutes.</p>
                    <p>If you did not request this, please ignore this email.</p>
                ", function ($message) use ($email) {
                $message->to($email)->subject('Your OTP Code');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send OTP via email: ' . $e->getMessage());
            return response()->json([
                'message' => 'User registered, but failed to send OTP via email.',
            ], 500);
        }

        // Insert default settings
        DB::table('settings')->insert(['user_id' => $userId]);

        $user = DB::table('users')->find($userId);

        return response()->json([
            'message' => 'User registered successfully. OTP sent via email.',
            'user'    => $user,
        ], 201);
    }

    /* SMS OTP */

//    public function register(Request $request): JsonResponse
//    {
//        $validator = Validator::make($request->all(), [
//            'name'         => ['required', 'string', 'max:255'],
//            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255'],
//            'country_code' => ['required', 'string'],
//            'mobile'       => ['required', 'string'],
//            'password'     => ['required', 'confirmed', 'min:6'],
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'message' => 'All fields are required.',
//                'errors'  => $validator->messages()
//            ], 400);
//        }
//
//        $countryCode = ltrim($request->input('country_code'), '+');
//        $localMobile = $request->input('mobile');
//        $fullMobile  = $countryCode . $localMobile;
//
//        /* ---------- Check if user already exists ---------- */
//        $existingUser = DB::table('users')
//            ->where('email',  $request->input('email'))
//            ->orWhere(function ($q) use ($localMobile, $countryCode) {
//                $q->where('mobile', $localMobile)
//                    ->where('country_code', $countryCode);
//            })
//            ->first();
//
//        $otp           = rand(1000, 9999);
//        $expiryMinutes = 3;
//
//        if ($existingUser) {
//
//            /* --- Already verified --- */
//            if ($existingUser->otp_verified_at) {
//                return response()->json([
//                    'message' => 'User already registered and verified.',
//                    'user'    => [
//                        'id'     => $existingUser->id,
//                        'name'   => $existingUser->name,
//                        'email'  => $existingUser->email,
//                        'mobile' => $existingUser->mobile,
//                        'register_step' => $existingUser->register_step,
//                    ]
//                ], 200);
//            }
//
//            /* --- Not verified: resend OTP --- */
//            DB::table('users')
//                ->where('id', $existingUser->id)
//                ->update([
//                    'otp'            => $otp,
//                    'otp_expires_at' => now()->addMinutes($expiryMinutes),
//                ]);
//
//            $sms = $this->sendMsg91($fullMobile, $otp, 'signup_otp', $expiryMinutes);
//
//            if (!$sms['success']) {
//                return response()->json([
//                    'message' => 'Failed to resend OTP. ' . $sms['message'],
//                ], 500);
//            }
//
//            return response()->json([
//                'message' => 'User is already registered but not verified. OTP resent.',
//                'user'    => [
//                    'id'     => $existingUser->id,
//                    'name'   => $existingUser->name,
//                    'email'  => $existingUser->email,
//                    'mobile' => $existingUser->mobile,
//                    'register_step' => $existingUser->register_step,
//                ],
//            ], 200);
//        }
//
//        /* ---------- 4. Create user (new registration) ---------- */
//        $userId = DB::table('users')->insertGetId([
//            'name'         => ucfirst($request->input('name')),
//            'email'        => $request->input('email'),
//            'country_code' => $countryCode,
//            'mobile'       => $localMobile,
//            'password'     => Hash::make($request->input('password')),
//            'device_token' => $request->input('device_token'),
//            'otp'          => $otp,
//            'otp_expires_at' => now()->addMinutes($expiryMinutes),
//            'register_step' => 0,
//        ]);
//
//        $sms = $this->sendMsg91($fullMobile, $otp, 'signup_otp', $expiryMinutes);
//
//        if (!$sms['success']) {
//            return response()->json([
//                'message' => 'User registered, but failed to send OTP. ' . $sms['message'],
//            ], 500);
//        }
//
//        // Insert default settings
//        DB::table('settings')->insert(['user_id' => $userId]);
//
//        $user = DB::table('users')->find($userId);
//        return response()->json([
//            'message' => 'User registered successfully. OTP sent for verification.',
//            'user'    => $user,
//        ], 201);
//    }


    public function VerifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'string', 'max:255'],
            'otp' => ['required', 'digits:4'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input.',
                'errors' => $validator->messages()
            ], 400);
        }

        $user = DB::table('users')->where('mobile', $request->input('mobile'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Check if OTP matches
        if ($user->otp !== $request->input('otp')) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        // Check if OTP is expired
        if (Carbon::parse($user->otp_expires_at)->lt(now())) {
            return response()->json([
                'message' => 'Your OTP has expired.',
            ], 400);
        }

        // Update user as verified
        DB::table('users')->where('id', $user->id)->update([
            'otp_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json(['message' => 'OTP verified successfully. User is now verified.'], 200);
    }


    public function sendEmailOtp(Request $request): JsonResponse
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input.',
                'errors' => $validator->messages()
            ], 400);
        }

        // Generate OTP (4-digit random number)
        $otp = mt_rand(1000, 9999);

        // Insert or update user email with OTP
        DB::table('users')->update(
            ['email_otp' => $otp, 'email_expires_at' => now()->addMinutes(3)],
        );

        // Send OTP via email
        Mail::to($request->input('email'))->send(new SendOtpMail($otp));

        return response()->json([
            'message' => 'OTP sent successfully to your email.',
        ], 200);
    }


    public function verifyEmailOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email_otp' => 'required|numeric',
            'email'     => 'required|email|max:255',
        ]);

        $email    = $request->input('email');
        $emailOtp = $request->input('email_otp');

        $user = DB::table('users')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.email', $email)
            ->select(
                'users.id as user_id', 'users.name', 'users.email', 'users.mobile', 'users.role',
                'user_details.gender', 'user_details.profile_image', 'users.register_step',
                'users.email_otp', 'users.email_expires_at'
            )
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ((int)$user->email_otp !== (int)$emailOtp) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        if (
            empty($user->email_expires_at) ||
            !strtotime($user->email_expires_at) ||
            now()->gt(Carbon::parse($user->email_expires_at))
        ) {
            return response()->json(['message' => 'Your OTP is expired.'], 400);
        }

        $updated = DB::table('users')
            ->where('email', $email)
            ->update([
                'email_otp'         => null,
                'email_expires_at'  => null,
                'email_verified_at' => now(),
            ]);

        if (is_null($user->gender)) {
            return response()->json([
                'message'       => 'User profile is incomplete. Please complete registration.',
                'user_id'       => $user->user_id,
                'register_step' => $user->register_step,
            ], 200);
        }



        if (!$updated) {
            return response()->json(['message' => 'OTP verified, but failed to update verification.'], 500);
        }

        Auth::loginUsingId($user->user_id);

        $imagePath     = public_path('Profile Image/' . $user->profile_image);
        $profileImage  = ($user->profile_image && file_exists($imagePath))
            ? asset('Profile Image/' . $user->profile_image)
            : '';

        $package = DB::table('receipts')
            ->where('user_id', $user->user_id)
            ->latest()
            ->value('package') ?? 'No Package';

        $isProfileComplete = ($user->register_step == 5);

        if (!$isProfileComplete) {
            return response()->json([
                'status'        => 'success',
                'message'       => 'User profile is incomplete. Please complete all registration steps.',
                'user_id'       => $user->user_id,
                'register_step' => $user->register_step,
            ], 200);
        }

        return response()->json([
            'message' => 'OTP verified successfully!',
            'user'    => [
                'id'            => $user->user_id,
                'name'          => $user->name,
                'email'         => $user->email,
                'mobile'        => $user->mobile,
                'role'          => $user->role,
                'profile_image' => $profileImage,
                'package'       => $package,
            ]
        ], 200);
    }


    public function RegistrationDetails(Request $request): JsonResponse
    {
        $user_id = $request->input('user_id');

        // Fetch all Tables from the database
        $tables = [
            'languages', 'marital_status', 'skin_tone', 'height', 'body_type', 'eating_habits',
            'drinking_habits', 'smoking_habits', 'religion', 'castes', 'sub_castes',
            'salary', 'employed_in', 'rashi', 'stars', 'lagnam', 'padam', 'kulam', 'gothram',
            'dosham', 'countries', 'property_details',
            'education_level', 'education', 'occupation_type', 'occupation', 'ethnicity', 'nationality', 'visa_status',
        ];

        $data = [];

        /*Education Table*/
//        $educationLevels = DB::table('education_level')
//            ->select('id  as level_id', 'name as level_name')
//            ->get()
//            ->map(function ($level) {
//                $level->educations = DB::table('education')
//                    ->where('education_level_id', $level->level_id)
//                    ->select('id', 'name')
//                    ->get();
//
//                return $level;
//            });
//        $data['education_level'] = $educationLevels;
//        $approval = DB::table('table_approvals')
//            ->where('table_name', 'education')
//            ->value('status');
//        $data['iseducation_levelEnabled'] = $approval === 'enable';

        /*Occupation Table*/
//        $occupationTypes = DB::table('occupation_type')
//            ->select('occupation_type.id as type_id', 'occupation_type.name as type_name')
//            ->get()->map(function ($type) {
//                $occupations = DB::table('occupation')
//                    ->where('occupation_type_id', $type->type_id)
//                    ->select('id', 'name')
//                    ->get();
//                $type->occupations = $occupations;
//                return $type;
//            });
//        $data['occupation_type'] = $occupationTypes;
//        $approval = DB::table('table_approvals')
//            ->where('table_name', 'occupation')
//            ->value('status');
//        $data['isoccupation_typeEnabled'] = $approval === 'enable';

        /*Everything else as-is */
        foreach ($tables as $table) {
            $data[$table] = DB::table($table)->get();
            $isEnabled = DB::table('table_approvals')
                    ->where('table_name', $table)
                    ->value('status') === 'enable';
            $data["is{$table}Enabled"] = $isEnabled;
        }

        // Return the user ID and all the fetched data
        return response()->json([
            'user_id' => $user_id,
            'get_registration_details' => $data,
        ]);
    }


    public function getStates(Request $request): JsonResponse
    {
        $states = DB::table('states')
            ->whereIn('country_id', function ($query) use ($request) {
                $query->select('id')->from('countries')->where('name', $request->input('country'));
            })
            ->pluck('name');

        return response()->json(['state_list' => $states]);
    }


    public function getCities(Request $request): JsonResponse
    {
        $cities = DB::table('cities')
            ->whereIn('state_id', function ($query) use ($request) {
                $query->select('id')->from('states')->where('name', $request->input('state'));
            })
            ->pluck('name');

        return response()->json(['city_list' => $cities]);
    }

    public function getOccupations(Request $request): JsonResponse
    {
        $occupations = DB::table('occupation')
            ->whereIn('occupation_type_id', function ($query) use ($request) {
                $query->select('id')->from('occupation_type')->where('name', $request->input('occupation_type'));
            })
            ->pluck('name');

        return response()->json(['occupation_list' => $occupations]);
    }

    public function getEducation(Request $request): JsonResponse
    {
        $educations = DB::table('education')
            ->whereIn('education_level_id', function ($query) use ($request) {
                $query->select('id')->from('education_level')->where('name', $request->input('education_level'));
            })
            ->pluck('name');

        return response()->json(['education_list' => $educations]);
    }

    public function getSubCastes(Request $request): JsonResponse
    {
        $subCastes = DB::table('sub_castes')
            ->whereIn('caste_id', function ($query) use ($request) {
                $query->select('id')->from('castes')->where('name', $request->input('caste'));
            })
            ->pluck('name');

        return response()->json(['sub_castes_list' => $subCastes]);
    }
    
    public function getAllEducation(Request $request): JsonResponse
    {
        $educationLevels = DB::table('education_level')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $educationData = [];

        foreach ($educationLevels as $level) {
            $educations = DB::table('education')
                ->where('education_level_id', $level->id)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            $educationData[] = [
                'level_id' => $level->id,
                'level_name' => $level->name,
                'educations' => $educations
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $educationData
        ]);
    }

    public function getAllOccupations(Request $request): JsonResponse
    {
        $occupationTypes = DB::table('occupation_type')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $occupationData = [];

        foreach ($occupationTypes as $type) {
            $occupations = DB::table('occupation')
                ->where('occupation_type_id', $type->id)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            $occupationData[] = [
                'type_id' => $type->id,
                'type_name' => $type->name,
                'occupations' => $occupations
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $occupationData
        ]);
    }

    public function GetRegisterPersonal(Request $request): JsonResponse
    {
        $tables = [
            'languages', 'marital_status', 'skin_tone', 'height', 'body_type', 'eating_habits',
            'drinking_habits', 'smoking_habits', 'religion', 'castes', 'sub_castes', 'ethnicity', 'nationality'
        ];

        $data = [];

        foreach ($tables as $table) {
            $data[$table] = DB::table($table)->get();
            $isEnabled = DB::table('table_approvals')
                    ->where('table_name', $table)
                    ->value('status') === 'enable';
            $data["is{$table}Enabled"] = $isEnabled;
        }

        return response()->json([
            'get_register_personal' => $data,
        ]);
    }

    public function GetRegisterCountries(Request $request): JsonResponse
    {
        $tables = [
            'countries',
        ];

        $data = [];

        foreach ($tables as $table) {
            $data[$table] = DB::table($table)->get();
            $isEnabled = DB::table('table_approvals')
                    ->where('table_name', $table)
                    ->value('status') === 'enable';
            $data["is{$table}Enabled"] = $isEnabled;
        }

        return response()->json([
            'get_register_countries' => $data,
        ]);
    }

    public function GetRegisterProfessional(Request $request): JsonResponse
    {
        $tables = [
            'education_level', 'occupation_type', 'employed_in', 'salary', 'visa_status'
        ];

        $data = [];

        foreach ($tables as $table) {
            $data[$table] = DB::table($table)->get();
            $isEnabled = DB::table('table_approvals')
                    ->where('table_name', $table)
                    ->value('status') === 'enable';
            $data["is{$table}Enabled"] = $isEnabled;
        }

        return response()->json([
            'get_register_professional' => $data,
        ]);
    }

    public function GetRegisterFamily(Request $request): JsonResponse
    {
        $tables = [
            'property_details',
        ];

        $data = [];

        foreach ($tables as $table) {
            $data[$table] = DB::table($table)->get();
            $isEnabled = DB::table('table_approvals')
                    ->where('table_name', $table)
                    ->value('status') === 'enable';
            $data["is{$table}Enabled"] = $isEnabled;
        }

        return response()->json([
            'get_register_family' => $data,
        ]);
    }

    public function GetRegisterHoroscope(Request $request): JsonResponse
    {
        $tables = [
            'rashi', 'stars', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham'
        ];

        $data = [];

        foreach ($tables as $table) {
            $data[$table] = DB::table($table)->get();
            $isEnabled = DB::table('table_approvals')
                    ->where('table_name', $table)
                    ->value('status') === 'enable';
            $data["is{$table}Enabled"] = $isEnabled;
        }

        return response()->json([
            'get_register_horoscope' => $data,
        ]);
    }

    public function RegisterPersonalDetails(Request $request): JsonResponse
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'profile_image' => 'nullable',
                'dob' => 'required|date',
                'birth_time' => 'nullable',
                'marital_status' => 'required|string',
                'religion' => 'required|string',
                'caste' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Convert birth time flexibly
            $birthTime = null;
            $hour = $request->input('hour') ?? $request->input('birth_hour');
            $minute = $request->input('minute') ?? $request->input('birth_minute') ?? '00';
            $ampm = $request->input('ampm') ?? $request->input('birth_ampm');

            if (!empty($hour) && !empty($ampm)) {
                $birthTime = date('H:i:s', strtotime("{$hour}:{$minute} {$ampm}"));
            } elseif ($request->filled('birth_time')) {
                try {
                    $birthTime = Carbon::createFromFormat('h:i A', $request->input('birth_time'))->format('H:i:s');
                } catch (\Exception $ex) {
                    $birthTime = date('H:i:s', strtotime($request->input('birth_time')));
                }
            }

            // Handle file or base64 uploads
            $profileImageName = null;
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                $profileImageName = date('dmYHis') . '.' . $profileImage->getClientOriginalExtension();
                $profileImage->move(public_path('Profile Image'), $profileImageName);
            } elseif ($request->filled('profile_image') && strpos($request->input('profile_image'), 'base64') !== false) {
                $image_parts = explode(";base64,", $request->input('profile_image'));
                if (isset($image_parts[1])) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $profileImageName = date('dmY_His') . '.jpg';
                    file_put_contents(public_path('Profile Image/' . $profileImageName), $image_base64);
                }
            }

            // Prepare data
            $user_id = $request->input('user_id');
            $personalDetails = array_merge($request->only([
                'profile_for', 'gender', 'dob', 'birth_country', 'birth_state', 'birth_city',
                'mother_tongue', 'marital_status', 'religion', 'caste', 'sub_caste', 'new_community',
                'ethnicity', 'nationality'
            ]), [
                'user_id' => $user_id,
                'birth_time' => $birthTime,
            ]);

            if ($profileImageName) {
                $personalDetails['profile_image'] = $profileImageName;
            }

            DB::table('user_details')->updateOrInsert(['user_id' => $user_id], $personalDetails);
            DB::table('users')->where('id', $user_id)->update(['register_step' => 1]);
            return response()->json([
                'status' => 'success',
                'message' => 'Personal Details registration completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong ' .$e->getMessage(),
            ]);
        }
    }

    public function RegisterPhysicalDetails(Request $request): JsonResponse
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'physical_status' => 'required|string',
                'skin_tone' => 'nullable|string',
                'height' => 'required|string',
                'weight' => 'required|string',
                'body_type' => 'nullable|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data
            $user_id = $request->input('user_id');
            $physicalDetails = array_merge($request->only([
                'physical_status', 'skin_tone', 'height', 'weight', 'body_type',
            ]), [
                'user_id' => $user_id,
            ]);

            DB::table('user_details')->updateOrInsert(['user_id' => $user_id], $physicalDetails);
            DB::table('users')->where('id', $user_id)->update(['register_step' => 2]);
            return response()->json([
                'status' => 'success',
                'message' => 'Physical Details registration completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong ' .$e->getMessage(),
            ]);
        }
    }

    public function RegisterHabitualDetails(Request $request): JsonResponse
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'eating_habit' => 'required|string',
                'drinking_habit' => 'nullable|string',
                'smoking_habit' => 'nullable|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data
            $user_id = $request->input('user_id');
            $habitualDetails = array_merge($request->only([
                'eating_habit', 'drinking_habit', 'smoking_habit',
            ]), [
                'user_id' => $user_id,
            ]);

            DB::table('user_details')->updateOrInsert(['user_id' => $user_id], $habitualDetails);
            DB::table('users')->where('id', $user_id)->update(['register_step' => 3]);
            return response()->json([
                'status' => 'success',
                'message' => 'Habitual Details registration completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong ' .$e->getMessage(),
            ]);
        }
    }

    public function RegisterProfessionalDetails(Request $request): JsonResponse
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'education' => 'required|string',
                'employed_in' => 'required|string',
                'occupation' => 'required|string',
                'monthly_income' => 'required|string',
                'work_country' => 'nullable|string',
                'visa_status' => 'nullable|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data
            $user_id = $request->input('user_id');
            $professionalDetails = array_merge($request->only([
                'qualification', 'education', 'employed_in', 'occupation_type', 'occupation',
                'monthly_income', 'work_country', 'visa_status', 'about_me'
            ]), [
                'user_id' => $user_id,
            ]);

            DB::table('user_details')->updateOrInsert(['user_id' => $user_id], $professionalDetails);
            DB::table('users')->where('id', $user_id)->update(['register_step' => 4]);
            return response()->json([
                'status' => 'success',
                'message' => 'Professional Details registration completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong ' .$e->getMessage(),
            ]);
        }
    }

    public function RegisterFamilyDetails(Request $request): JsonResponse
    {
        try {
            $user_id = $request->input('user_id');
            $propertyDetailsString = DataController::formatPropertyDetails($request->input('property_details'));

            $familyDetails = array_merge($request->only([
                'father_name', 'father_profession', 'mother_name', 'mother_profession', 'family_type',
                'family_status', 'family_values', 'family_god',
                'no_of_brother', 'elder_brother', 'younger_brother',
                'elder_married_brother', 'younger_married_brother',
                'no_of_sister', 'elder_sister', 'younger_sister',
                'elder_married_sister', 'younger_married_sister',
                'property_info', 'about_family', 'marriage_timeline'
            ]), [
                'user_id' => $user_id,
                'property_details' => $propertyDetailsString,
            ]);

            DB::table('user_details')->updateOrInsert(['user_id' => $user_id], $familyDetails);
            DB::table('users')->where('id', $user_id)->update(['register_step' => 5]);
            return response()->json([
                'status' => 'success',
                'message' => 'Family Details registration completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong ' .$e->getMessage(),
            ]);
        }
    }

    public function RegisterHoroscopeDetails(Request $request): JsonResponse
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'rashi' => 'required|string',
                'nakshatra' => 'nullable|string',
                'gothram' => 'nullable|string',
                'dosham' => 'required|string',
                'horoscope_image' => 'nullable'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file or base64 uploads
            $horoscopeImageName = null;
            if ($request->hasFile('horoscope_image')) {
                $horoscopeImage = $request->file('horoscope_image');
                $horoscopeImageName = date('dmYHis') . '.' . $horoscopeImage->getClientOriginalExtension();
                $horoscopeImage->move(public_path('Horoscope Image'), $horoscopeImageName);
            } elseif ($request->filled('horoscope_image') && strpos($request->input('horoscope_image'), 'base64') !== false) {
                $image_parts = explode(";base64,", $request->input('horoscope_image'));
                if (isset($image_parts[1])) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $horoscopeImageName = date('dmY_His') . '.jpg';
                    file_put_contents(public_path('Horoscope Image/' . $horoscopeImageName), $image_base64);
                }
            }

            // Prepare data
            $user_id = $request->input('user_id');
            $horoscopeDetails = array_merge($request->only([
                'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham'
            ]), [
                'user_id' => $user_id,
            ]);

            if ($horoscopeImageName) {
                $horoscopeDetails['horoscope_image'] = $horoscopeImageName;
            }

            DB::table('user_details')->updateOrInsert(['user_id' => $user_id], $horoscopeDetails);
            DB::table('users')->where('id', $user_id)->update(['register_step' => 6]);
            return response()->json([
                'status' => 'success',
                'message' => 'Horoscope Details registration completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong ' .$e->getMessage(),
            ]);
        }
    }

    public function RegisterAddressDetails(Request $request): JsonResponse
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'country' => 'required|string',
                'state' => 'required|string',
                'city' => 'required|string',
                'address' => 'required|string',
                'pin_code' => 'required|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user_id = $request->input('user_id');

            $addressDetails = array_merge(
                $request->only([
                    'country',
                    'state',
                    'listed_state',
                    'new_state',
                    'city',
                    'listed_city',
                    'new_city',
                    'address',
                    'pin_code',
                    'latitude',
                    'longitude',
                ]),
                [
                    'user_id' => $user_id,
                ]
            );

            DB::table('user_details')->updateOrInsert(
                ['user_id' => $user_id],
                $addressDetails
            );

            DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'register_step' => 7,
                    'status' => 'pending',
                ]);

            $user = DB::table('users')
                ->where('id', $user_id)
                ->first();

            $html = "
                <html lang='en'>
                <body style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
                    <div style='max-width:600px; margin:auto; background:#fff; border-radius:8px; padding:20px; box-shadow:0 0 10px rgba(0,0,0,0.1);'>
                        <h2 style='color:#efb331;'>Hi {$user->name},</h2>

                        <p>
                            🎉 Your registration is <strong>completed</strong>!
                        </p>

                        <p>
                            Thank you for registering with Bala Matrimony Bureau.
                            Your account is currently under verification.
                            Once verified, you will receive a confirmation
                            that your account has been activated.
                        </p>

                        <br>

                        <p style='margin-top: 20px;'>
                            Regards,<br>
                            <strong>Bala Matrimony Bureau</strong>
                        </p>
                    </div>
                </body>
                </html>
            ";

            Mail::html($html, function ($message) use ($user) {
                $message
                    ->to($user->email)
                    ->subject('Registration Completed – Welcome to Bala Matrimony Bureau!');
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Registration completed successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong ' . $e->getMessage(),
            ], 500);
        }
    }



    public function StoreRegistrationDetails(Request $request): JsonResponse
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'profile_image' => 'nullable',
            'horoscope_image' => 'nullable',
            'profile_for' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'birth_time' => 'nullable',
            'marital_status' => 'required|string',
            'religion' => 'required|string',
            'caste' => 'nullable|string',
            'education' => 'required|string',
            'employed_in' => 'required|string',
            'occupation' => 'required|string',
            'monthly_income' => 'required|string',
            'rashi' => 'required|string',
            'nakshatra' => 'nullable|string',
            'dosham' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Convert birth time flexibly
        $birthTime = null;
        $hour = $request->input('hour') ?? $request->input('birth_hour');
        $minute = $request->input('minute') ?? $request->input('birth_minute') ?? '00';
        $ampm = $request->input('ampm') ?? $request->input('birth_ampm');

        if (!empty($hour) && !empty($ampm)) {
            $birthTime = date('H:i:s', strtotime("{$hour}:{$minute} {$ampm}"));
        } elseif ($request->filled('birth_time')) {
            try {
                $birthTime = Carbon::createFromFormat('h:i A', $request->input('birth_time'))->format('H:i:s');
            } catch (\Exception $ex) {
                $birthTime = date('H:i:s', strtotime($request->input('birth_time')));
            }
        }

        // Handle file uploads or base64
        $profileImageName = null;
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '_profile.' . $profileImage->getClientOriginalExtension();
            $profileImage->move(public_path('Profile Image'), $profileImageName);
        } elseif ($request->filled('profile_image') && strpos($request->input('profile_image'), 'base64') !== false) {
            $image_parts = explode(";base64,", $request->input('profile_image'));
            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $profileImageName = date('dmY_His') . '.jpg';
                file_put_contents(public_path('Profile Image/' . $profileImageName), $image_base64);
            }
        }

        $horoscopeImageName = null;
        if ($request->hasFile('horoscope_image')) {
            $horoscopeImage = $request->file('horoscope_image');
            $horoscopeImageName = time() . '_horoscope.' . $horoscopeImage->getClientOriginalExtension();
            $horoscopeImage->move(public_path('Horoscope Image'), $horoscopeImageName);
        } elseif ($request->filled('horoscope_image') && strpos($request->input('horoscope_image'), 'base64') !== false) {
            $image_parts = explode(";base64,", $request->input('horoscope_image'));
            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $horoscopeImageName = date('dmY_His') . '.jpg';
                file_put_contents(public_path('Horoscope Image/' . $horoscopeImageName), $image_base64);
            }
        }

        // Format property details
        $propertyDetailsString = DataController::formatPropertyDetails($request->input('property_details'));

        // Prepare data
        $user_id = $request->input('user_id');
        $registerDetails = array_merge($request->only([
            'profile_for', 'gender', 'dob', 'birth_country', 'birth_state', 'birth_city',
            'mother_tongue', 'marital_status', 'skin_tone', 'height', 'weight', 'body_type', 'physical_status',
            'eating_habit', 'drinking_habit', 'smoking_habit', 'religion', 'caste', 'new_community', 'sub_caste', 'ethnicity', 'nationality',
            'about_me', 'qualification', 'education', 'employed_in', 'occupation_type',
            'occupation', 'monthly_income', 'work_country', 'visa_status',
            'father_name', 'father_profession', 'mother_name', 'mother_profession', 'family_type', 'family_status', 'family_values', 'family_god',
            'no_of_brother', 'elder_brother', 'younger_brother', 'elder_married_brother', 'younger_married_brother',
            'no_of_sister', 'elder_sister', 'younger_sister', 'elder_married_sister', 'younger_married_sister',
            'property_info', 'about_family', 'marriage_timeline',
            'rashi', 'nakshatra', 'lagnam', 'padam', 'kulam', 'gothram', 'dosham',
            'address', 'country', 'state', 'new_state', 'city', 'new_city', 'pin_code', 'coupon_code', 'latitude', 'longitude'
        ]), [
            'user_id' => $user_id,
            'birth_time' => $birthTime,
            'property_details' => $propertyDetailsString,
            'profile_image' => $profileImageName,
            'horoscope_image' => $horoscopeImageName,
            'listed_community' => $request->has('listed_community') && $request->input('listed_community') ? 1 : 0,
            'listed_state' => $request->has('listed_state') && $request->input('listed_state') ? 1 : 0,
            'listed_city' => $request->has('listed_city') && $request->input('listed_city') ? 1 : 0,
        ]);

        $deviceToken = $request->input('device_token');
        DB::table('users')->where('id', $user_id)->update(['device_token' => $deviceToken, 'status' => 'pending']);

        // Insert or update record
        DB::table('user_details')->updateOrInsert(
            ['user_id' => $user_id],
            $registerDetails
        );

        /* --- Notify users of the opposite gender --- */
        $user = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('user_details.user_id', $user_id)
            ->first();

        $countryCode = ltrim($user->country_code, '+');
        $mobileNumber = $countryCode . $user->mobile;
        $this->sendMsg91Flow($mobileNumber, 'account_activation_pending');

        if ($user) {
            $userName = $user->name;
            $oppositeGender = $user->gender === 'male' ? 'female' : 'male';
            $oppositeGenderUsers = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_details.gender', $oppositeGender)
                ->select('users.id', 'users.device_token')
                ->get();

            foreach ($oppositeGenderUsers as $oppositeUser) {
//                $oppositeUserModel = User::find($oppositeUser->id);
//                $oppositeUserModel->notify(new NewProfileNotification($userName));

                /* --- Firebase Notification --- */
                if ($oppositeUser->device_token) {
                    $title = 'New Profile Registered';
                    $body = "A new profile for {$userName} has been registered!";
                    $this->fcmController->sendFcmNotificationHelper($oppositeUser->device_token, $title, $body);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile submitted for approval successfully',
            'user' => [
                'id' => $user_id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'role' => $user->role,
                'profile_image' => asset('Profile Image/' . $user->profile_image),
            ]
        ]);
    }


    public function Login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $username = $request->input('username');
        $password = $request->input('password');
        $fieldType = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (!Auth::attempt([$fieldType => $username, 'password' => $password])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        if ($user->status === 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is under review and will be activated shortly.'
            ]);
        }

        if ($user->status === 'deactivated') {
            Auth::logout();
            return response()->json([
                'status' => false,
                'message' => 'Your account has been deactivated. Please contact support.'
            ], 403);
        }

        if (!$user->email_verified_at) {
            $otp = rand(1000, 9999);
            $user->email_otp = $otp;
            $user->email_expires_at = now()->addMinutes(3);
            $user->save();

            $user->notify(new SendOtpNotification($otp));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent to your email. Please verify your OTP.',
                'id' => $user->id,
            ], 200);
        }

        $user->device_token = $request->device_token;
        $user->save();

        $userDetails = DB::table('user_details')->where('user_id', $user->id)->first();

        $profileImage = $userDetails && $userDetails->profile_image && file_exists(public_path('Profile Image/' . $userDetails->profile_image))
            ? asset('Profile Image/' . $userDetails->profile_image)
            : '';

        $package = DB::table('receipts')->where('user_id', $user->id)->value('package') ?? 'No Package';

//        $showTour = false;
//        if ($user->show_tour === 1) {
//            $showTour = true;
//            $user->show_tour = 0;
//            $user->save();
//        }

        // Check if register_step is complete (assumed 7 steps)
        $isProfileComplete = ($user->register_step == 7);

        if (!$isProfileComplete) {
            return response()->json([
                'status' => 'success',
                'message' => 'User profile is incomplete. Please complete all registration steps.',
                'user_id' => $user->id,
                'register_step' => $user->register_step,
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User exists and OTP is verified. Login successful.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'role' => $user->role,
                'gender' => $userDetails->gender ?? '',
                'profile_for' => $userDetails->profile_for ?? '',
                'profile_image' => $profileImage,
                'package' => $package,
//                'show_tour' => $showTour,
                'register_step' => $user->register_step,
            ]
        ], 200);
    }



    public function adminLogin(Request $request): JsonResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'device_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $deviceToken = $request->device_token;

        // Find all users with the role of 'admin'
        $adminUsers = User::where('role', 'admin')->get();

        // Check if any admin users exist
        if ($adminUsers->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No admin users found.',
            ], 404);
        }

        // Update the device token for each admin
        foreach ($adminUsers as $adminUser) {
            $adminUser->device_token = $deviceToken;
            $adminUser->save();
        }

        return response()->json([
            'url' => url('login?access=app'),
        ], 200);
    }



    public function Logout(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $deviceToken = $request->input('device_token');
        DB::table('users')->where('id', $userId)->update(['device_token' => null]);

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function ForgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }
        $otp = rand(1000, 9999);
        DB::table('users')
            ->where('email', $email)
            ->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(3),
        ]);
        Mail::to($user->email)->send(new PasswordResetOtp($otp));
        return response()->json(['message' => 'OTP sent to your email']);
    }


    public function ResetPasswordVerifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:4',
        ]);

        $email = $request->input('email');
        $otp   = $request->input('otp');
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        if (Carbon::parse($user->otp_expires_at)->lt(now())) {
            return response()->json(['message' => 'Your OTP has expired'], 400);
        }

        if ($user->otp !== $otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'otp'            => null,
                'otp_expires_at' => null,
                'otp_verified_at'=> now(),
            ]);
        return response()->json(['message' => 'OTP verified successfully.']);
    }


    public function ResetChangePassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
        ]);

        $email = $request->input('email');

        // Get the user by email
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found.'], 404);
        }

        DB::table('users')->where('email', $email)->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['message' => 'Password changed successfully.']);
    }


    public function SendOtpLogin(Request $request): JsonResponse
    {
        $request->validate([
            'country_code' => 'required',
            'mobile' => 'required|string|max:15',
        ]);

        $mobile = $request->input('mobile');
        $countryCode = ltrim($request->input('country_code'), '+');
        $mobileNumber = $countryCode . $mobile;
        $user = DB::table('users')
            ->where('mobile', $mobile)
            ->where('country_code', $countryCode)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Mobile number not found!'], 404);
        }

        if ($user->status !== 'active') {
            return response()->json(
                ['message' => 'Your account is under review. Please wait for approval!'],
                403
            );
        }

        $otp = rand(1000, 9999);
        DB::table('users')
            ->where('mobile', $mobile)
            ->where('country_code', $countryCode)
            ->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(3),
            ]);

         $response = $this->sendMsg91($mobileNumber, $otp, 'login_otp');
         if ($response['success']) {
            return response()->json(['message' => 'OTP sent successfully.'], 200);
         } else {
            return response()->json(['message' => $response['error']], 500);
         }
    }



    public function verifyOtpLogin(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|numeric',
            'mobile' => 'required|string|max:15',
        ]);

        $otp = $request->input('otp');
        $mobile = $request->input('mobile');

        $user = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.mobile', $mobile)
            ->select(
                'users.id as user_id', 'users.name', 'users.email', 'users.mobile', 'users.role', 'user_details.gender',
                'user_details.profile_image', 'users.otp', 'users.otp_expires_at'
            )
            ->first();

        // Case 1: User exists without details
        if (!$user) {
            $basicUser = DB::table('users')
                ->where('mobile', $mobile)
                ->select('id as user_id')
                ->first();

            if ($basicUser) {
                return response()->json([
                    'message' => 'User Profile is incomplete. Please register your details.',
                    'user_id' => $basicUser->user_id,
                ], 200);
            }

            return response()->json(['message' => 'User not found!'], 404);
        }

        // Case 2: User details incomplete
        if (is_null($user->gender)) {
            return response()->json(['message' => 'User details not found!'], 404);
        }

        // Case 3: OTP mismatch
        if ($user->otp != $otp) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        // Case 4: OTP matched and not expired
        if (Carbon::parse($user->otp_expires_at)->greaterThanOrEqualTo(now())) {
            DB::table('users')
                ->where('mobile', $mobile)
                ->update([
                    'otp' => null,
                    'otp_expires_at' => null,
                    'otp_verified_at' => now(),
                ]);

            Auth::loginUsingId($user->user_id);

            $imagePath = public_path('Profile Image/' . $user->profile_image);
            $profileImage = $user->profile_image && file_exists($imagePath)
                ? asset('Profile Image/' . $user->profile_image)
                : '';

            $package = DB::table('receipts')
                ->where('user_id', $user->user_id)
                ->value('package') ?? 'No Package';

            return response()->json([
                'message' => 'OTP verified successfully!',
                'user' => [
                    'id' => $user->user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'role' => $user->role,
                    'profile_image' => $profileImage,
                    'package' => $package,
                ]
            ], 200);
        }

        return response()->json(['message' => 'Your OTP is expired.'], 400);
    }



    public function deactivateUserAccount(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $reason = $request->input('reason');
        $status = 'deactivated';

        DB::table('users')->where('id', '=', $userId)
            ->update([
                'status' => $status,
                'reason' => $reason,
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Your account has been deleted successfully!'
        ]);
    }

    public function getVerificationStatus(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $user = DB::table('users')->where('id', '=', $userId)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Verification status fetched successfully',
                'photo_verification_status' => $user->photo_verified_at ?? 'not verified',
                'mobile_verification_status' => $user->otp_verified_at ? 'verified' : 'not verified',
                'mail_verification_status' => $user->email_verified_at ? 'verified' : 'not verified',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function photoVerification(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $user = DB::table('users')->where('id', '=', $userId)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ]);
            }

            $ImageName = null;
            if ($request->hasFile('photo')) {
                $profileImage = $request->file('photo');
                $ImageName = date('dmYHis') . '.' . $profileImage->getClientOriginalExtension();
                $profileImage->move(public_path('Photos'), $ImageName);
            }

            DB::table('users')->where('id', '=', $userId)->update([
                'photo' => $ImageName,
                'photo_verified_at' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image submitted for approval successfully',
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function getUpdateLimit(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $user = DB::table('users')->where('id', '=', $userId)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $totalUpdate = DB::table('profile_update_settings')->value('count');

        if (is_null($totalUpdate)) {
            return response()->json([
                'status' => false,
                'message' => 'Profile update limit is not set.',
                'limit_reached' => false
            ], 404);
        }

        $usedUpdate = $user->profile_update_count ?? 0;
        $remainingUpdate = max($totalUpdate - $usedUpdate, 0);
        $limitReached = $usedUpdate >= $totalUpdate;

        return response()->json([
            'status' => true,
            'total_update' => $totalUpdate,
            'used_update' => $usedUpdate,
            'remaining_update' => $remainingUpdate,
            'limit_reached' => $limitReached
        ]);
    }


//    Only Testing realtime
//    public function testSendNotification(Request $request): JsonResponse
//    {
//        $request->validate([
//            'token' => 'required|string',
//            'title' => 'required|string',
//            'body' => 'required|string',
//        ]);
//
//        $fcmToken = $request->token;
//        $title = $request->title;
//        $body = $request->body;
//
//        // Load the Firebase credentials file
//        $projectId = 'pg-matrimony';
//        $credentialsFilePath = storage_path('app/json/firebaseSdk.json');
//
//        // Create Google Client
//        $client = new GoogleClient();
//        $client->setAuthConfig($credentialsFilePath);
//        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
//        $client->refreshTokenWithAssertion();
//        $accessToken = $client->getAccessToken()['access_token'];
//
//        // Prepare notification payload
//        $data = [
//            "message" => [
//                "token" => $fcmToken,
//                "notification" => [
//                    "title" => $title,
//                    "body" => $body,
//                ],
//            ]
//        ];
//
//        $payload = json_encode($data);
//
//        // Set up cURL for the API request
//        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
//        $headers = [
//            "Authorization: Bearer $accessToken",
//            "Content-Type: application/json",
//        ];
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//
//        $response = curl_exec($ch);
//        $error = curl_error($ch);
//        curl_close($ch);
//
//        if ($error) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Failed to send notification',
//                'error' => $error,
//            ], 500);
//        }
//
//        return response()->json([
//            'status' => 'success',
//            'message' => 'Notification sent successfully',
//            'response' => json_decode($response, true),
//        ]);
//    }

}
