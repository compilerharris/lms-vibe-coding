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
            
            // Get current session ID before checking
            $currentSessionId = $request->session()->getId();
            
            // Check if user has an active session on another device
            // Note: We exclude the current session by ID, so even if it's in DB, it won't be counted
            $activeSession = $this->getActiveSession($user->id, $currentSessionId);
            
            if ($activeSession) {
                // Store user ID and remember flag (not password for security)
                $request->session()->put('pending_login', [
                    'user_id' => $user->id,
                    'remember' => $remember,
                ]);
                
                // Store session info for display
                $request->session()->put('active_session_info', [
                    'ip_address' => $activeSession->ip_address,
                    'user_agent' => $activeSession->user_agent,
                    'last_activity' => $activeSession->last_activity,
                ]);
                
                // Logout temporarily to show confirmation page
                Auth::logout();
                
                return redirect()->route('login.confirm');
            }
            
            // No active session, proceed with normal login
            $request->session()->regenerate();
            
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

    public function showConfirmLogin()
    {
        if (!session()->has('pending_login')) {
            return redirect()->route('login');
        }

        $sessionInfo = session()->get('active_session_info', []);
        
        return view('auth.confirm-login', [
            'sessionInfo' => $sessionInfo,
        ]);
    }

    public function forceLogin(Request $request)
    {
        if (!session()->has('pending_login')) {
            return redirect()->route('login');
        }

        $pendingLogin = session()->get('pending_login');
        $user = \App\Models\User::find($pendingLogin['user_id']);

        if (!$user) {
            session()->forget(['pending_login', 'active_session_info']);
            return redirect()->route('login')->withErrors([
                'email' => 'User not found.',
            ]);
        }

        // Delete all existing sessions for this user
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        // Clear pending login data
        session()->forget(['pending_login', 'active_session_info']);

        // Regenerate session
        $request->session()->regenerate();

        // Log the user in
        Auth::login($user, $pendingLogin['remember']);

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

    public function cancelLogin()
    {
        session()->forget(['pending_login', 'active_session_info']);
        Auth::logout();
        return redirect()->route('login')->with('message', 'Login cancelled. Please use your existing session.');
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

    /**
     * Get active session for user (excluding current session)
     */
    private function getActiveSession($userId, $currentSessionId = null)
    {
        $sessionLifetime = config('session.lifetime', 120);
        $lastActivity = now()->subMinutes($sessionLifetime)->timestamp;

        $query = DB::table('sessions')
            ->where('user_id', $userId)
            ->where('last_activity', '>', $lastActivity);

        if ($currentSessionId) {
            $query->where('id', '!=', $currentSessionId);
        }

        return $query->first();
    }
}
