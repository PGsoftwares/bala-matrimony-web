<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataSharedController;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\TrackLogin;
use App\Models\User;
use App\Traits\MailService;
use App\Traits\Msg91;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use Msg91;
    use MailService;
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $metaTags = DataSharedController::MetaData('login');
        $db = DataSharedController::getDatabases();
        return view('auth.login', compact('metaTags','db'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to authenticate the user
        $request->authenticate();

        // Regenerate the session ID to prevent session fixation attacks
        $request->session()->regenerate();

        // Retrieve the authenticated user
        $loggedInUser = $request->user();
        $loggedInUserRole = $loggedInUser->role;
        $userStatus = $loggedInUser->status;

        // Check for 1-year inactivity and deactivate
        if (
            $userStatus !== 'deactivated' &&
            $loggedInUser->last_login_at &&
            Carbon::parse($loggedInUser->last_login_at)->lt(now()->subYear())
        ) {
            $loggedInUser->status = 'deactivated';
            $loggedInUser->save();
            return $this->logoutAndRedirect(route('login'), 'Your account has been inactive for over a year. Please contact support for assistance.');
        }

        // If the user is not deactivated, update the login timestamp
        if ($userStatus !== 'deactivated') {
            $loggedInUser->login_at = now();
            $loggedInUser->logout_at = null;
        }

        // Handle a login attempt and count for non-admin users
        if ($loggedInUserRole !== 'admin') {
            $allowedLogins = DB::table('track_broker')->value('no_of_login') ?? 5;
            $today = now()->startOfDay();

            // Check if it's a new login day
            if ($loggedInUser->last_login_at && Carbon::parse($loggedInUser->last_login_at)->startOfDay() !== $today) {
                $loggedInUser->login_count = 1;
            } else {
                $loggedInUser->increment('login_count');
            }

            $loggedInUser->last_login_at = now();

            // Track broker login limit check
            // Email alert disabled to prevent automated spam emails
            /*
            if ($loggedInUser->login_count > $allowedLogins) {
                try {
                    $this->sendMailableEMail('laravelpgsoftwares@gmail.com', new TrackLogin($loggedInUser));
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
            */
        }

        // Save user data after any updates
        $loggedInUser->save();

        // If the user account is deactivated, log them out and invalidate the session
        if ($userStatus === 'deactivated') {
            return $this->logoutAndRedirect(route('login'), 'Your account has been deactivated. Please contact support.');
        }

        // Redirect based on the user's role
        return match ($loggedInUserRole) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'professional' => redirect()->intended(route('professional.dashboard')),
            default => $this->handleUserFlowAfterLogin($loggedInUser),
        };
    }

    /**
     * Handle the logout and redirect with an error message.
     */
    private function logoutAndRedirect(string $route, string $message): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect($route)->withErrors(['account' => $message]);
    }

    private function handleUserFlowAfterLogin(User $user): RedirectResponse
    {
        // First-time login tour
//        if (
//            $user->show_tour
//            && $user->register_step >= 7
//            && $user->status === 'active'
//        ) {
//            return redirect()->route('tour1');
//        }
        return $this->handleUserRegistrationSteps($user);
    }


    /**
     * Handle the redirection for incomplete user registration steps.
     */
    private function handleUserRegistrationSteps(User $user): RedirectResponse
    {
        $step = $user->register_step;
        $step = is_null($step) ? null : (int) $step;

        /* ───────────── 1. Brand‑new user needs OTP ───────────── */
        if ($step === null) {
            if ($this->sendRegistrationMailOtp($user)) {
                // stay on step 0 until OTP verified
                return redirect()
                    ->route('verifyRegisterEmailPage', ['email' => $user->email])
                    ->with('success', 'OTP sent to your email successfully');
            }

            return $this->logoutAndRedirect(
                route('login'),
                'We could not send an OTP right now – please try again later.'
            );
        }

        // ─────── Step 7 but status is still pending ───────
        if ($step >= 7 && $user->status === 'pending') {
            return $this->logoutAndRedirect(
                route('login'),
                'Your profile is under review. You will be notified once it is approved.'
            );
        }

        /* ───────────── 2. Unfinished profile wizard ───────────── */
        return match ($step) {
            0       => redirect()->intended(route('registerStep1')),
            1       => redirect()->intended(route('registerStep2')),
            2       => redirect()->intended(route('registerStep3')),
            3       => redirect()->intended(route('registerStep4')),
            4       => redirect()->intended(route('registerStep5')),
            5       => redirect()->intended(route('registerStep6')),
            6       => redirect()->intended(route('registerStep7')),
            default => redirect()->intended(route('dashboard')),
        };
    }


    private function sendRegistrationMailOtp(User $user): bool
    {
        $emailOTP           = random_int(1000, 9999);
        $expiryMinutes = 3;

        DB::table('users')->where('id', $user->id)->update([
            'email_otp'            => $emailOTP,
            'email_expires_at' => now()->addMinutes($expiryMinutes),
        ]);

        try {
            $this->sendHtmlEmailOtp($user, $emailOTP, $expiryMinutes);
            return true;
        } catch (\Exception $e) {
            Log::error('OTP Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    private function sendRegistrationOtp(User $user): bool
    {
        $otp           = random_int(1000, 9999);
        $expiryMinutes = 3;

        // update the user record (no model events triggered)
        DB::table('users')->where('id', $user->id)->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes($expiryMinutes),
        ]);

        $mobileNumber = ltrim($user->country_code, '+').$user->mobile;

        $response = $this->sendMsg91($mobileNumber, $otp, 'signup_otp', $expiryMinutes);

        if (! $response['success']) {
            Log::error('OTP‑SMS failed: '.$response['message']);
            return false;
        }

        return true;
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $loggedInUser = Auth::guard('web')->user();

        $loggedInUser?->forceFill([
            'logout_at' => now(),
            'login_at' => null,
        ])->save();

        // Logout the user and invalidate the session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
