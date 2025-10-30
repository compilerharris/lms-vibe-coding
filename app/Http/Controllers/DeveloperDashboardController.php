<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeveloperDashboardController extends Controller
{
    /**
     * Display the developer dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isDeveloper()) {
            abort(403, 'Unauthorized access.');
        }

        // Get developer's projects
        $projects = Project::where('developer_user_id', $user->id)
            ->where('is_active', true)
            ->withCount('leads')
            ->get();

        // Get analytics data
        $analytics = $this->getAnalytics($user->id);
        
        // Get recent leads
        $recentLeads = Lead::whereHas('project', function($query) use ($user) {
                $query->where('developer_user_id', $user->id);
            })
            ->with(['project', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get only mapped channel partners for this developer
        $mappings = \App\Models\DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $user->id)
            ->where('is_active', true)
            ->with('channelPartnerUser')
            ->get();

        $channelPartners = $mappings->pluck('channelPartnerUser')->filter();

        // Annotate leads count for each mapped CP (for this developer's projects only)
        $channelPartners->transform(function($cp) use ($user) {
            if ($cp) {
                $cp->leads_count = Lead::where('assigned_user_id', $cp->id)
                    ->whereHas('project', function($q) use ($user) {
                        $q->where('developer_user_id', $user->id);
                    })
                    ->count();
            }
            return $cp;
        });

        return view('developer.dashboard', compact('projects', 'analytics', 'recentLeads', 'channelPartners'));
    }

    /**
     * Get analytics data for the developer.
     */
    private function getAnalytics($userId)
    {
        try {
            // Total leads count
            $totalLeads = Lead::whereHas('project', function($query) use ($userId) {
                $query->where('developer_user_id', $userId);
            })->count();

            // Leads by status
            $leadsByStatus = Lead::whereHas('project', function($query) use ($userId) {
                    $query->where('developer_user_id', $userId);
                })
                ->get()
                ->groupBy('status')
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray();

            // Leads by project
            $leadsByProject = Lead::whereHas('project', function($query) use ($userId) {
                    $query->where('developer_user_id', $userId);
                })
                ->with('project')
                ->get()
                ->groupBy('project.name')
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray();

            // Monthly leads trend (last 6 months)
            $monthlyLeads = Lead::whereHas('project', function($query) use ($userId) {
                    $query->where('developer_user_id', $userId);
                })
                ->where('created_at', '>=', now()->subMonths(6))
                ->get()
                ->groupBy(function($lead) {
                    return $lead->created_at->format('Y-m');
                })
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray();

            // Channel partner performance
            $cpPerformance = User::whereHas('role', function($query) {
                $query->where('name', 'channel_partner');
            })
            ->with(['assignedLeads' => function($query) {
                $query->where('status', 'converted');
            }])
            ->get()
            ->mapWithKeys(function($cp) {
                return [$cp->name => $cp->assignedLeads->count()];
            })
            ->toArray();

            // Conversion rate
            $convertedLeads = Lead::whereHas('project', function($query) use ($userId) {
                    $query->where('developer_user_id', $userId);
                })
                ->where('status', 'converted')
                ->count();

            $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;

            return [
                'totalLeads' => $totalLeads,
                'leadsByStatus' => $leadsByStatus,
                'leadsByProject' => $leadsByProject,
                'monthlyLeads' => $monthlyLeads,
                'cpPerformance' => $cpPerformance,
                'conversionRate' => $conversionRate,
                'convertedLeads' => $convertedLeads,
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting developer analytics: ' . $e->getMessage());
            
            // Return default values if there's an error
            return [
                'totalLeads' => 0,
                'leadsByStatus' => [],
                'leadsByProject' => [],
                'monthlyLeads' => [],
                'cpPerformance' => [],
                'conversionRate' => 0,
                'convertedLeads' => 0,
            ];
        }
    }

    /**
     * Show project details (read-only for developers).
     */
    public function showProject(Project $project)
    {
        $user = Auth::user();
        
        // Ensure the authenticated developer owns this project
        if (!$user->isDeveloper() || $project->developer_user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Load assigned user (channel partner user) for project leads
        $project->load(['developer', 'leads.assignedUser']);
        
        // Get project analytics
        $projectAnalytics = [
            'totalLeads' => $project->leads->count(),
            'leadsByStatus' => $project->leads->groupBy('status')->map->count()->toArray(),
            // Group by assigned user (CP) name
            'leadsByChannelPartner' => $project->leads->groupBy('assignedUser.name')->map->count()->toArray(),
            'conversionRate' => $project->leads->count() > 0 ? 
                round(($project->leads->where('status', 'converted')->count() / $project->leads->count()) * 100, 2) : 0,
        ];

        return view('developer.project-details', compact('project', 'projectAnalytics'));
    }
}
