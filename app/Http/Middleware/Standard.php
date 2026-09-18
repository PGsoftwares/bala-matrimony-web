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
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        return match ($userRole) {
            'standard' => $next($request),
            'professional' => redirect()->route('professional.dashboard'),
            'admin', 'staff_admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('/'),
        };
    }
}
