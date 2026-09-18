<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\DataSharedController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Traits\SMSTrait;

class NewPasswordController extends Controller
{
    use SMSTrait;
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        $db = DataSharedController::getDatabases();
        return view('auth.reset-password', ['request' => $request, 'db' => $db]);
    }

    /**
     * Handle an incoming new password request.
     *
     */
//    Password Reset SMS OTP
//    public function store(Request $request): RedirectResponse
//    {
//        $request->validate([
//            'token' => ['required'],
//            'email' => ['required', 'email'],
//            'password' => ['required', 'confirmed', Rules\Password::defaults()],
//        ]);
//
//        try {
//            // Attempt to reset the password
//            $status = Password::reset(
//                $request->only('email', 'password', 'password_confirmation', 'token'),
//                function ($user) use ($request) {
//                    $user->forceFill([
//                        'password' => Hash::make($request->password),
//                        'remember_token' => Str::random(60),
//                    ])->save();
//
//                    event(new PasswordReset($user));
//
//                    // Send SMS notification
//                    if ($user->mobile) {
//                        $smsSent = $this->sendSMS($user->mobile, 'pwdChanged', ['NAME' => $user->name]);
//                        if (!$smsSent) {
//                            Log::warning('Password changed SMS notification failed for mobile: ' . $user->mobile);
//                        }
//                    }
//                }
//            );
//
//            // Redirect based on the status
//            return $status == Password::PASSWORD_RESET
//                ? redirect()->route('login')->with('status', __($status))
//                : back()->withInput($request->only('email'))
//                    ->withErrors(['email' => __($status)]);
//        } catch (\Exception $e) {
//            // Log any exceptions and show a generic error
//            Log::error('Error during password reset or SMS notification: ' . $e->getMessage());
//
//            return back()->withInput($request->only('email'))
//                ->withErrors(['email' => 'Unable to process your request at this time. Please try again later.']);
//        }
//    }

// Password Reset Email Verify
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise, we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
