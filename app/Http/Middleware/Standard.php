<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Standard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is not logged in, redirect to the login page
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        $userRole = $user->role;

        if ($userRole === 'standard') {
            $step = $user->register_step;
            $step = is_null($step) ? null : (int) $step;

            // Incomplete users (register_step < 7 or null) cannot access completed profile routes
            if ($step === null || $step < 7) {
                // If the user is on a registration step or logout route, allow access
                if (
                    $request->routeIs(
                        'registerStep1', 'storeRegisterStep1',
                        'registerStep2', 'storeRegisterStep2',
                        'registerStep3', 'storeRegisterStep3',
                        'registerStep4', 'storeRegisterStep4',
                        'registerStep5', 'storeRegisterStep5',
                        'registerStep6', 'storeRegisterStep6',
                        'registerStep7', 'storeRegisterStep7',
                        'logout'
                    )
                ) {
                    return $next($request);
                }

                // Redirect incomplete users to their pending registration step
                $targetRoute = match ($step) {
                    1 => 'registerStep2',
                    2 => 'registerStep3',
                    3 => 'registerStep4',
                    4 => 'registerStep5',
                    5 => 'registerStep6',
                    6 => 'registerStep7',
                    default => 'registerStep1',
                };

                return redirect()->route($targetRoute)->with('info', 'Please complete your registration first.');
            }

            // Completed users (register_step >= 7) must have active status
            if ($user->status !== 'active') {
                Auth::logout();
                if ($request->hasSession()) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }

                if ($user->status === 'pending') {
                    return redirect()->route('login')->withErrors([
                        'account' => 'Your profile is under review. You will be notified once it is approved.'
                    ]);
                }

                return redirect()->route('login')->withErrors([
                    'account' => 'Your account is not active. Please contact support.'
                ]);
            }

            // If a completed active user tries to visit the registration wizard, redirect to dashboard
            if ($request->routeIs('registerStep*', 'storeRegisterStep*')) {
                return redirect()->route('dashboard');
            }

            return $next($request);
        }

        return match ($userRole) {
            'professional' => redirect()->route('professional.dashboard'),
            'admin', 'staff_admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('/'),
        };
    }
}
