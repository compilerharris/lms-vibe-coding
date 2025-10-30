<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\User;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isLeader()) {
            return $this->leaderDashboard();
        } elseif ($user->isDeveloper()) {
            return $this->developerDashboard();
        } elseif ($user->isChannelPartner()) {
            return $this->channelPartnerDashboard();
        }
        
        return $this->defaultDashboard();
    }

    private function adminDashboard()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'assigned_leads' => Lead::where('status', 'assigned')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'total_cps' => User::whereHas('role', function($query) {
                $query->where('name', 'channel_partner');
            })->count(),
            'active_projects' => Project::where('is_active', true)->count(),
        ];

        $recent_leads = Lead::with(['project', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recent_leads'));
    }

    private function leaderDashboard()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'assigned_leads' => Lead::where('status', 'assigned')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'total_cps' => User::whereHas('role', function($query) {
                $query->where('name', 'channel_partner');
            })->count(),
            'active_projects' => Project::where('is_active', true)->count(),
        ];

        $recent_leads = Lead::with(['project', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.leader', compact('stats', 'recent_leads'));
    }

    private function developerDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'total_leads' => Lead::whereHas('project', function($query) use ($user) {
                $query->where('developer_user_id', $user->id);
            })->count(),
            'new_leads' => Lead::where('status', 'new')
                ->whereHas('project', function($query) use ($user) {
                    $query->where('developer_user_id', $user->id);
                })->count(),
            'assigned_leads' => Lead::where('status', 'assigned')
                ->whereHas('project', function($query) use ($user) {
                    $query->where('developer_user_id', $user->id);
                })->count(),
            'converted_leads' => Lead::where('status', 'converted')
                ->whereHas('project', function($query) use ($user) {
                    $query->where('developer_user_id', $user->id);
                })->count(),
            'total_cps' => User::whereHas('role', function($query) {
                $query->where('name', 'channel_partner');
            })->count(),
            'active_projects' => Project::where('developer_user_id', $user->id)->where('is_active', true)->count(),
        ];

        $recent_leads = Lead::with(['project', 'assignedUser'])
            ->whereHas('project', function($query) use ($user) {
                $query->where('developer_user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.developer', compact('stats', 'recent_leads'));
    }

    private function channelPartnerDashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_leads' => Lead::where('assigned_user_id', $user->id)->count(),
            'new_leads' => Lead::where('assigned_user_id', $user->id)
                ->where('status', 'new')->count(),
            'assigned_leads' => Lead::where('assigned_user_id', $user->id)
                ->where('status', 'assigned')->count(),
            'converted_leads' => Lead::where('assigned_user_id', $user->id)
                ->where('status', 'converted')->count(),
            'total_cps' => 1, // Only this channel partner
            'active_projects' => Project::where('is_active', true)->count(),
        ];

        $recent_leads = Lead::with(['project'])
            ->where('assigned_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.cp', compact('stats', 'recent_leads'));
    }

    private function defaultDashboard()
    {
        return view('dashboard.default');
    }
}