<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Professional
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is not logged in, redirect to the login page
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        return match ($userRole) {
            'professional' => $next($request),
            'admin' => redirect()->route('admin.dashboard'),
            'standard' => redirect()->route('dashboard'),
            default => redirect()->route('/'),
        };
    }
}
