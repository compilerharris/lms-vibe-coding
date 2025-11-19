<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user already has an active session in database
            $sessionLifetime = config('session.lifetime', 120);
            $lastActivity = now()->subMinutes($sessionLifetime)->timestamp;
            
            $activeSession = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '>', $lastActivity)
                ->first();
            
            // If user has an active session, prevent login
            if ($activeSession) {
                Auth::logout();
                
                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'You are already logged in on another device. Please logout from that device first or wait for the session to expire.',
                    ]);
            }
            
            // No active session, proceed with login
            $request->session()->regenerate();
            
            // Logout other devices using Laravel's built-in method
            $this->authenticate();
            
            // Save session and update with user_id
            $request->session()->save();
            $newSessionId = $request->session()->getId();
            DB::table('sessions')
                ->where('id', $newSessionId)
                ->update(['user_id' => $user->id]);
            
            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isLeader()) {
                return redirect()->route('leader.dashboard');
            } elseif ($user->isDeveloper()) {
                return redirect()->route('developer.dashboard');
            } elseif ($user->isChannelPartner()) {
                return redirect()->route('cp.dashboard');
            } elseif ($user->isCS() || $user->isBiddable()) {
                return redirect()->route('cs.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Delete all sessions for this user
        if ($userId) {
            DB::table('sessions')
                ->where('user_id', $userId)
                ->delete();
        }
        
        return redirect()->route('login');
    }

    protected function authenticate()
    {
        Auth::logoutOtherDevices(request()->password);
    }

}
