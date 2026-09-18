<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Models\User;
use App\Traits\MailService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Traits\Msg91;

class RegisteredUserController extends Controller
{
    use Msg91;
    use MailService;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $metaTags = DataSharedController::MetaData('register');
        $db = DataSharedController::getDatabases();
        return view('auth.register', compact('metaTags', 'db'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_for' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', 'numeric', 'digits:10', 'unique:users,mobile'],
            'country_code' => ['required'],
            'password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@#$%^&+=!]{6,}$/',
                'confirmed'
            ],
        ], [
            'name.regex' => 'Name must start with an uppercase letter followed by lowercase letters',
            'mobile.numeric' => 'Mobile number must contain only numbers',
            'password.regex' => 'Password must be at least 6 characters and include both letters and numbers'
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => Str::title(strtolower($request->name)),
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_code' => $request->country_code,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
            Auth::login($user);

            DB::table('settings')->insert(['user_id' => $user->id]);
            DB::table('user_details')->insert([
                'user_id' => $user->id,
                'profile_for' => $request->input('profile_for'),
                'gender' => $request->input('gender'),
            ]);

            $otp = rand(1000, 9999);
            $expiryMinutes = 3;

            DB::table('users')->where('id', $user->id)->update([
                'email_otp' => $otp,
                'email_expires_at' => now()->addMinutes($expiryMinutes),
            ]);

            // Send OTP via email
            $this->sendOtpEmail($user->email, $otp);

            DB::commit();

            return redirect()->route('verifyRegisterEmailPage', ['email' => $user->email])
                ->with('success', 'OTP sent to your email successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }


    /*--- SMS OTP Registration ---*/
//    public function store(Request $request): RedirectResponse
//    {
//        $request->validate([
//            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
//            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
//            'mobile' => ['required', 'numeric', 'digits:10', 'unique:users,mobile'],
//            'country_code' => ['required'],
//            'password' => [
//                'required',
//                'min:6',
//                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@#$%^&+=!]{6,}$/',
//                'confirmed'
//            ],
//        ], [
//            'name.regex' => 'Name must start with an uppercase letter followed by lowercase letters',
//            'mobile.numeric' => 'Mobile number must contain only numbers',
//            'password.regex' => 'Password must be at least 6 characters and include both letters and numbers'
//        ]);
//
//        DB::beginTransaction();
//        try {
//            $user = User::create([
//                'name' => Str::title(strtolower($request->name)),
//                'email' => $request->email,
//                'mobile' => $request->mobile,
//                'country_code' => $request->country_code,
//                'password' => Hash::make($request->password),
//                'status' => 'pending',
//            ]);
//
//            event(new Registered($user));
//            Auth::login($user);
//
//            DB::table('settings')->insert(['user_id' => $user->id]);
//            DB::table('user_details')->insert(['user_id' => $user->id]);
//
//            $otp = rand(1000, 9999);
//            $expiryMinutes = 3;
//            DB::table('users')->where('id', $user->id)->update([
//                'otp' => $otp,
//                'otp_expires_at' => now()->addMinutes($expiryMinutes),
//            ]);
//            $countryCode = ltrim($request->country_code, '+');
//            $mobileNumber = $countryCode . $request->mobile;
//            $otpResponse = $this->sendMsg91($mobileNumber, $otp, 'signup_otp', $expiryMinutes);
//
//            if (!$otpResponse['success']) {
//                DB::rollBack();
//                return back()->withErrors(['otp' => $otpResponse['message']]);
//            }
//
//            DB::commit();
//            return redirect()->route('verify-register-otp', ['mobile' => $request->mobile])
//                ->with('success', 'OTP sent successfully.');
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
//        }
//    }

}
