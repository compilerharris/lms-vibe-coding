<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class CSDashboardController extends Controller
{
    /**
     * Display the CS/Biddable dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all leads with related data
        $leads = Lead::with(['project.developer', 'assignedUser'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Get analytics data
        $analytics = $this->getAnalytics();

        // Get recent leads (last 10)
        $recentLeads = $leads->take(10);

        // Get leads by status
        $leadsByStatus = $leads->groupBy('status')
                              ->map(function($group) {
                                  return $group->count();
                              })
                              ->toArray();

        // Get leads by project
        $leadsByProject = $leads->groupBy('project.name')
                               ->map(function($group) {
                                   return $group->count();
                               })
                               ->toArray();

        // Get leads by channel partner (assigned user)
        $leadsByChannelPartner = $leads->whereNotNull('assigned_user_id')
                                      ->groupBy('assignedUser.name')
                                      ->map(function($group) {
                                          return $group->count();
                                      })
                                      ->toArray();

        // Monthly leads trend (last 6 months)
        $monthlyLeads = $leads->where('created_at', '>=', now()->subMonths(6))
                             ->groupBy(function($lead) {
                                 return $lead->created_at->format('Y-m');
                             })
                             ->map(function($group) {
                                 return $group->count();
                             })
                             ->toArray();

        return view('cs.dashboard', compact(
            'analytics',
            'recentLeads',
            'leadsByStatus',
            'leadsByProject',
            'leadsByChannelPartner',
            'monthlyLeads'
        ));
    }

    /**
     * Show lead details (read-only).
     */
    public function showLead(Lead $lead)
    {
        $lead->load(['project.developer', 'assignedUser']);
        
        return view('cs.lead-details', compact('lead'));
    }

    /**
     * Get analytics data for CS dashboard.
     */
    private function getAnalytics()
    {
        try {
            // Total leads count
            $totalLeads = Lead::count();

            // Converted leads count
            $convertedLeads = Lead::where('status', 'converted')->count();

            // Conversion rate
            $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;

            // Active projects count
            $activeProjectsCount = Project::where('is_active', true)->count();

            // Active channel partners count
            $activeChannelPartnersCount = User::whereHas('role', function($query) {
                $query->where('name', 'channel_partner');
            })->count();

            // Active developers count
            $activeDevelopersCount = User::whereHas('role', function($query) {
                $query->where('name', 'developer');
            })->count();

            // Leads created today
            $leadsToday = Lead::whereDate('created_at', today())->count();

            // Leads created this week
            $leadsThisWeek = Lead::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count();

            // Leads created this month
            $leadsThisMonth = Lead::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->count();

            return [
                'totalLeads' => $totalLeads,
                'convertedLeads' => $convertedLeads,
                'conversionRate' => $conversionRate,
                'activeProjectsCount' => $activeProjectsCount,
                'activeChannelPartnersCount' => $activeChannelPartnersCount,
                'activeDevelopersCount' => $activeDevelopersCount,
                'leadsToday' => $leadsToday,
                'leadsThisWeek' => $leadsThisWeek,
                'leadsThisMonth' => $leadsThisMonth,
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting CS analytics: ' . $e->getMessage());
            
            // Return default values if there's an error
            return [
                'totalLeads' => 0,
                'convertedLeads' => 0,
                'conversionRate' => 0,
                'activeProjectsCount' => 0,
                'activeChannelPartnersCount' => 0,
                'activeDevelopersCount' => 0,
                'leadsToday' => 0,
                'leadsThisWeek' => 0,
                'leadsThisMonth' => 0,
            ];
        }
    }
}