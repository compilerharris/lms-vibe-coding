<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class CPDashboardController extends Controller
{
    /**
     * Display the Channel Partner dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isChannelPartner()) {
            abort(403, 'Unauthorized access.');
        }

        // Get analytics data
        $analytics = $this->getAnalytics($user);

        // Get assigned leads for this CP
        $assignedLeads = Lead::where('assigned_user_id', $user->id)
            ->with(['project'])
            ->get();

        // Get leads by status
        $leadsByStatus = $assignedLeads->groupBy('status')
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        // Get leads by project
        $leadsByProject = $assignedLeads->groupBy('project.name')
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        // Get monthly leads trend (last 6 months)
        $monthlyLeads = $assignedLeads
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(function($lead) {
                return $lead->created_at->format('Y-m');
            })
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        // Get recent leads
        $recentLeads = $assignedLeads
            ->sortByDesc('created_at')
            ->take(10);

        return view('cp.dashboard', [
            'channelPartner' => $user,
            'developer' => null, // Not needed in user-based system
            'analytics' => $analytics,
            'assignedLeads' => $assignedLeads,
            'leadsByStatus' => $leadsByStatus,
            'leadsByProject' => $leadsByProject,
            'monthlyLeads' => $monthlyLeads,
            'recentLeads' => $recentLeads
        ]);
    }

    /**
     * Show a specific lead (read-only for CPs).
     */
    public function showLead(Lead $lead)
    {
        $user = Auth::user();
        
        if (!$user->isChannelPartner()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this lead is assigned to the current CP
        if ($lead->assigned_user_id !== $user->id) {
            abort(403, 'You can only view leads assigned to you.');
        }

        $lead->load(['project', 'assignedUser']);
        
        // The channel partner is the authenticated user viewing their own lead
        $channelPartner = $user;
        
        return view('cp.lead-details', compact('lead', 'channelPartner'));
    }

    /**
     * Get analytics data for the channel partner.
     */
    private function getAnalytics($user)
    {
        try {
            $assignedLeads = Lead::where('assigned_user_id', $user->id)->get();

            // Total leads count
            $totalLeads = $assignedLeads->count();

            // Leads by status
            $leadsByStatus = $assignedLeads->groupBy('status')
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray();

            // Leads by project
            $leadsByProject = $assignedLeads->groupBy('project.name')
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray();

            // Monthly leads trend (last 6 months)
            $monthlyLeads = $assignedLeads
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy(function($lead) {
                    return $lead->created_at->format('Y-m');
                })
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray();

            // Conversion rate
            $convertedLeads = $assignedLeads->where('status', 'converted')->count();
            $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;

            // Additional CP metrics used by the view
            $leadsToday = $assignedLeads->where('created_at', '>=', now()->startOfDay())->count();
            $leadsThisWeek = $assignedLeads->where('created_at', '>=', now()->startOfWeek())->count();
            $leadsThisMonth = $assignedLeads->where('created_at', '>=', now()->startOfMonth())->count();
            $activeProjectsCount = $assignedLeads
                ->pluck('project_id')
                ->filter()
                ->unique()
                ->count();

            return [
                'totalLeads' => $totalLeads,
                'leadsByStatus' => $leadsByStatus,
                'leadsByProject' => $leadsByProject,
                'monthlyLeads' => $monthlyLeads,
                'conversionRate' => $conversionRate,
                'convertedLeads' => $convertedLeads,
                'leadsToday' => $leadsToday,
                'leadsThisWeek' => $leadsThisWeek,
                'leadsThisMonth' => $leadsThisMonth,
                'activeProjectsCount' => $activeProjectsCount,
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting CP analytics: ' . $e->getMessage());
            
            // Return default values if there's an error
            return [
                'totalLeads' => 0,
                'leadsByStatus' => [],
                'leadsByProject' => [],
                'monthlyLeads' => [],
                'conversionRate' => 0,
                'convertedLeads' => 0,
                'leadsToday' => 0,
                'leadsThisWeek' => 0,
                'leadsThisMonth' => 0,
                'activeProjectsCount' => 0,
            ];
        }
    }

    /**
     * Get empty analytics for when no developer is mapped.
     */
    private function getEmptyAnalytics()
    {
        return [
            'totalLeads' => 0,
            'leadsByStatus' => [],
            'leadsByProject' => [],
            'monthlyLeads' => [],
            'conversionRate' => 0,
            'convertedLeads' => 0,
        ];
    }
}