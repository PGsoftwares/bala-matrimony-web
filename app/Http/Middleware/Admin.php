<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class Admin
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
            'admin', 'staff_admin' => $next($request),
            'professional' => redirect()->route('professional.dashboard'),
            'standard' => redirect()->route('dashboard'),
            default => redirect()->route('/'),
        };
    }
}
