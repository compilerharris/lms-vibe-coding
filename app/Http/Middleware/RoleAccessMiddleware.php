<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $user = auth()->user();
        
        if (!$user->role) {
            return redirect()->route('login')->with('error', 'Your account is not properly configured. Please contact administrator.');
        }

        if (!in_array($user->role->name, $roles)) {
            // Redirect to appropriate dashboard based on user role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to access this page.');
            } elseif ($user->isLeader()) {
                return redirect()->route('leader.dashboard')->with('error', 'You do not have permission to access this page.');
            } elseif ($user->isDeveloper()) {
                return redirect()->route('developer.dashboard')->with('error', 'You do not have permission to access this page.');
            } elseif ($user->isChannelPartner()) {
                return redirect()->route('cp.dashboard')->with('error', 'You do not have permission to access this page.');
            } else {
                return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}