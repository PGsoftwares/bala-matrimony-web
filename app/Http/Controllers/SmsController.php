<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DataController;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Traits\MailService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Traits\SMSTrait;
use App\Traits\Msg91;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    use SMSTrait;
    use Msg91;
    use MailService;

    public function loginOtpPage(): View
    {
        $db = DataSharedController::getDatabases();
        return view('auth.login-otp', compact('db'));
    }

    public function verifyOtpPage($mobile): View
    {
        $db = DataSharedController::getDatabases();
        return view('auth.verify-otp', compact('mobile', 'db'));
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'country_code' => 'required|string',
            'mobile'       => 'required|numeric',
        ]);

        $mobile = $request->input('mobile');
        $countryCode = ltrim($request->input('country_code'), '+');
        $mobileNumber = $countryCode . $mobile;

        $user = DB::table('users')
            ->where('country_code', $countryCode)
            ->where('mobile', $mobile)
            ->first();

        if (!$user) {
            return back()->withErrors(['mobile' => 'Mobile number not found.']);
        }

        if ($user->status !== 'active') {
            return back()->withErrors(['mobile' => 'Your account is under review. Please wait for approval!']);
        }

        $otp = rand(1000, 9999);
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(3),
            ]);
        $response = $this->sendMsg91($mobileNumber, $otp, 'login_otp');

        if (!empty($response['success'])) {
            return redirect()->route('verify-otp', ['mobile' => $mobile])
                ->with('success', 'OTP sent successfully.');
        }
        return back()->withErrors(['otp' => 'Failed to send OTP. Please try again later.']);
    }


    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|numeric',
            'mobile' => 'required|string|max:15',
        ]);

        $otp = $request->input('otp');
        $mobile_number = $request->input('mobile');

        // Retrieve the user based on the mobile number from users table
        $user = DB::table('users')
            ->where('mobile', $mobile_number) // Check mobile in users table
            ->select('id', 'otp', 'otp_expires_at')
            ->first();

        if ($user) {
            // Check if the OTP matches and is not expired
            if ($user->otp == $otp && Carbon::now()->lt(Carbon::parse($user->otp_expires_at))) {
                // OTP is valid, mark OTP as verified in the users table
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['otp_verified_at' => now()]);

                // Log the user in
                Auth::loginUsingId($user->id);

                // Redirect to the dashboard
                return redirect()->route('dashboard')->with('success', 'OTP verified successfully!');
            } else {
                return back()->withErrors(['otp' => 'The OTP is either incorrect or has expired.']);
            }
        } else {
            return back()->withErrors(['mobile' => 'Mobile number not found!']);
        }
    }


    public function verifyRegisterOtpPage($mobile): View
    {
        $db = DataSharedController::getDatabases();
        return view('auth.verify-register-otp', compact('mobile', 'db'));
    }

    public function verifyRegisterOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|numeric',
            'mobile' => 'required|string|max:15',
        ]);

        $otp = $request->input('otp');
        $mobile_number = $request->input('mobile');
        $user = DB::table('users')
            ->where('mobile', $mobile_number)->first();

        if ($user) {
            if ($user->otp == $otp && Carbon::now()->lt(Carbon::parse($user->otp_expires_at))) {
                DB::table('users')
                    ->where('mobile', $mobile_number)
                    ->update([
                        'otp' => null,
                        'otp_verified_at' => now(),
                        'otp_expires_at' => null,
                    ]);
                Auth::loginUsingId($user->id);
                return redirect()->route('registerStep1')->with('success', 'Your mobile number has been verified successfully');
            } else {
                return back()->withErrors(['otp' => 'The OTP is either incorrect or has expired.']);
            }
        } else {
            return back()->withErrors(['mobile' => 'Mobile number not found!']);
        }
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'mobile' => 'required|string|max:15',
        ]);
        $mobileNumber = $request->input('mobile');
        $user = DB::table('users')->where('mobile', $mobileNumber)->first();

        if ($user) {
            $otp = rand(1000, 9999);
            DB::table('users')->where('mobile', $mobileNumber)->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(3),
            ]);

            // Send OTP via MSG91
            $response = $this->sendMsg91($mobileNumber, $otp, 'login');

            if ($response['success']) {
                return redirect()->route('verify-register-otp', ['mobile' => $mobileNumber]);
            } else {
                return back()->withErrors(['otp' => 'Failed to send OTP']);
            }
            // Send OTP via SMS co3 API
//            if ($this->sendSMS($mobileNumber, 'otpLogin', ['OTP' => $otp])) {
//                return redirect()->route('verify-otp', ['mobile' => $mobileNumber]);
//            } else {
//                return back()->withErrors(['otp' => 'Failed to send OTP']);
//            }

        }
        return back()->withErrors(['mobile' => 'Mobile number not found!']);
    }


    /* --- Mail OTP Functions --- */
    public function mailOTP(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        if ($user->status !== 'active') {
            return back()->withErrors(['email' => 'Your account is under review. Please wait for approval!']);
        }

        $otp = rand(1000, 9999);

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'email_otp' => $otp,
                'email_expires_at' => now()->addMinutes(3),
            ]);

        try {
            $this->sendLoginOtpEmail($email, $otp);
            return redirect()->route('showOtpForm', ['email' => $email])
                ->with('success', 'OTP sent successfully to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Failed to send OTP. Please try again later.']);
        }
    }

    public function showOtpForm(Request $request): View
    {
        $db = DataSharedController::getDatabases();
        $email = $request->query('email');
        return view('auth.verify-otp', compact('email', 'db'));
    }

    public function verifyEmailOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'email_otp' => 'required|numeric|digits:4',
        ]);

        $user = DB::table('users')
            ->where('email', $request->input('email'))
            ->where('email_otp', $request->input('email_otp'))
            ->where('email_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        DB::table('users')->where('id', $user->id)->update([
            'email_otp' => null,
            'email_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        Auth::loginUsingId($user->id);
        return redirect()->route('dashboard')->with('success', 'OTP verified successfully. Logged in.');
    }

    public function verifyRegisterEmailPage(Request $request): View
    {
        $email = $request->query('email');
        $userAndUserDetails = DataController::getUserDetails(Auth::id());
        $db = DataSharedController::getDatabases();
        return view('auth.verify-register-otp', compact('email', 'db', 'userAndUserDetails'));
    }

    public function verifyRegisterEmailOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email_otp' => 'required|numeric',
            'email' => 'required|email',
        ]);

        $otp = $request->input('email_otp');
        $email = $request->input('email');

        $user = DB::table('users')
            ->where('email', $email)
            ->where('email_otp', $otp)
            ->where('email_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'The OTP is either incorrect or has expired.']);
        }

        // OTP is valid and not expired
        DB::table('users')->where('email', $email)->update([
            'email_otp' => null,
            'email_verified_at' => now(),
            'email_expires_at' => null,
            'register_step' => 0,
        ]);

        Auth::loginUsingId($user->id);

        return redirect()->route('registerStep1')->with('success', 'Your email has been verified successfully.');
    }

    public function resendMailOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found']);
        }

        // Generate new OTP and update
        $emailOTP = rand(1000, 9999);
        $expiryMinutes = 3;

        DB::table('users')->where('id', $user->id)->update([
            'email_otp' => $emailOTP,
            'email_expires_at' => now()->addMinutes($expiryMinutes),
        ]);

        try {
            $this->sendHtmlEmailOtp($user, $emailOTP, $expiryMinutes);
            return back()->with('success', 'A new OTP has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('Resend OTP Email failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }
    }


}
