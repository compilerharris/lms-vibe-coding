<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isLeader()) {
            $leads = Lead::with(['project', 'assignedUser'])->get();
        } elseif ($user->isDeveloper()) {
            $leads = Lead::whereHas('project', function($query) use ($user) {
                $query->where('developer_user_id', $user->id);
            })->with(['project', 'assignedUser'])->get();
        } elseif ($user->isChannelPartner()) {
            $leads = Lead::where('assigned_user_id', $user->id)
                ->with(['project'])
                ->get();
        } else {
            $leads = collect();
        }

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        // Only Admin and Leader can create leads
        if (!Auth::user()->isAdmin() && !Auth::user()->isLeader()) {
            abort(403, 'You do not have permission to create leads.');
        }
        
        $projects = Project::where('is_active', true)->with('developer')->get();
        return view('leads.create', compact('projects'));
    }

    public function store(Request $request)
    {
        // Only Admin and Leader can store leads
        if (!Auth::user()->isAdmin() && !Auth::user()->isLeader()) {
            abort(403, 'You do not have permission to create leads.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'source' => 'nullable|string',
            'project_id' => 'required|uuid|exists:projects,id',
        ]);

        $lead = Lead::create($request->all());

        // Auto-assign lead using round-robin logic
        $this->assignLeadToChannelPartner($lead);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created and assigned successfully.');
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        // Only Admin and Leader can edit leads
        if (!Auth::user()->isAdmin() && !Auth::user()->isLeader()) {
            abort(403, 'You do not have permission to edit leads.');
        }
        
        $projects = Project::where('is_active', true)->with('developer')->get();

        // Filter channel partners by mapping with the selected lead's project's developer
        $channelPartners = collect();
        $developerUserId = optional($lead->project)->developer_user_id;
        if ($developerUserId) {
            $mappings = \App\Models\DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $developerUserId)
                ->where('is_active', true)
                ->with('channelPartnerUser')
                ->get();

            $channelPartners = $mappings->pluck('channelPartnerUser')->filter();
        }
        return view('leads.edit', compact('lead', 'projects', 'channelPartners'));
    }

    public function update(Request $request, Lead $lead)
    {
        // Only Admin and Leader can update leads
        if (!Auth::user()->isAdmin() && !Auth::user()->isLeader()) {
            abort(403, 'You do not have permission to edit leads.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'source' => 'nullable|string',
            'project_id' => 'required|uuid|exists:projects,id',
            'assigned_user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:new,assigned,contacted,converted,lost',
        ]);

        $lead->update($request->all());

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        // Only Admin and Leader can delete leads
        if (!Auth::user()->isAdmin() && !Auth::user()->isLeader()) {
            abort(403, 'You do not have permission to delete leads.');
        }
        
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    private function assignLeadToChannelPartner(Lead $lead)
    {
        $project = $lead->project;
        $developerUser = $project->developer;
        
        if (!$developerUser) {
            Log::warning('No developer user found for project', [
                'lead_id' => $lead->id,
                'project_id' => $project->id
            ]);
            return;
        }

        // Get mapped channel partners for this developer
        $mappedChannelPartners = \App\Models\DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $developerUser->id)
            ->where('is_active', true)
            ->with(['channelPartnerUser'])
            ->get()
            ->filter(function($mapping) {
                return $mapping->channelPartnerUser && !is_null($mapping->channelPartnerUser->cp_number);
            })
            ->sortBy(function($mapping) {
                return $mapping->channelPartnerUser->cp_number;
            })
            ->values();

        if ($mappedChannelPartners->isEmpty()) {
            Log::warning('No channel partners with cp_number mapped to developer for lead assignment', [
                'lead_id' => $lead->id,
                'developer_user_id' => $developerUser->id,
                'developer_name' => $developerUser->name,
                'project_id' => $project->id,
                'project_name' => $project->name
            ]);
            return;
        }

        // Get the last assigned lead for this project (same developer and project)
        $lastAssignedLead = Lead::whereNotNull('assigned_user_id')
            ->where('project_id', $project->id)
            ->where('id', '!=', $lead->id) // Exclude current lead
            ->orderBy('assigned_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->with('assignedUser')
            ->first();

        $lastAssignedCpNumber = null;
        
        // If there's a last assigned lead, get its CP's cp_number
        if ($lastAssignedLead && $lastAssignedLead->assignedUser) {
            $lastAssignedCpNumber = $lastAssignedLead->assignedUser->cp_number;
        }

        // Find the next CP number to assign (ascending order from smaller to bigger)
        $selectedMapping = null;
        $nextCpNumber = null;

        if ($lastAssignedCpNumber) {
            // Find CPs with cp_number greater than the last assigned one
            $nextMappings = $mappedChannelPartners->filter(function($mapping) use ($lastAssignedCpNumber) {
                return $mapping->channelPartnerUser->cp_number > $lastAssignedCpNumber;
            });
            
            if ($nextMappings->isNotEmpty()) {
                // Get the one with the smallest cp_number greater than last assigned
                $selectedMapping = $nextMappings->sortBy(function($mapping) {
                    return $mapping->channelPartnerUser->cp_number;
                })->first();
                $nextCpNumber = $selectedMapping->channelPartnerUser->cp_number;
            }
        }

        // If next CP number doesn't exist, assign to the lowest CP number
        if (!$selectedMapping) {
            $selectedMapping = $mappedChannelPartners->first(); // Already sorted, so first is lowest
            $nextCpNumber = $selectedMapping->channelPartnerUser->cp_number;
        }
        
        $selectedCP = $selectedMapping->channelPartnerUser;

        if (!$selectedCP || !$selectedMapping) {
            Log::warning('No active mapped channel partners available for lead assignment', [
                'lead_id' => $lead->id,
                'developer_user_id' => $developerUser->id,
                'total_mapped_cps' => $mappedChannelPartners->count()
            ]);
            return;
        }

        // Assign the lead to the selected mapped channel partner
        $lead->update([
            'assigned_user_id' => $selectedCP->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        Log::info('Lead assigned to mapped channel partner using CP number-based assignment from leads table', [
            'lead_id' => $lead->id,
            'assigned_user_id' => $selectedCP->id,
            'channel_partner_name' => $selectedCP->name,
            'cp_number' => $nextCpNumber,
            'developer_user_id' => $developerUser->id,
            'developer_name' => $developerUser->name,
            'project_id' => $project->id,
            'project_name' => $project->name,
            'last_assigned_cp_number' => $lastAssignedCpNumber,
            'assigned_cp_number' => $nextCpNumber,
            'total_mapped_cps' => $mappedChannelPartners->count()
        ]);
    }
}