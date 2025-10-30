<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LeadApiController extends Controller
{
    /**
     * Create a new lead via API
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'source' => 'nullable|string|max:255',
                'message' => 'nullable|string',
                'developer_alt_name' => 'required|string|exists:users,alt_name',
                'project_alt_name' => 'required|string|exists:projects,alt_name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify that the project belongs to the specified developer
            $developer = User::where('alt_name', $request->developer_alt_name)
                ->whereHas('role', function($query) {
                    $query->where('name', 'developer');
                })->first();
            
            $project = Project::where('alt_name', $request->project_alt_name)
                ->where('developer_user_id', $developer->id)
                ->where('is_active', true)
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found or does not belong to the specified developer'
                ], 404);
            }

            // Create the lead
            $lead = Lead::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'source' => $request->source,
                'message' => $request->message,
                'project_id' => $project->id,
                'status' => 'new'
            ]);

            // Assign lead to channel partner using round-robin
            $this->assignLeadToChannelPartner($lead);

            // Log the successful lead creation
            Log::info('Lead created via API', [
                'lead_id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email,
                'project_id' => $lead->project_id,
                'developer_alt_name' => $request->developer_alt_name,
                'project_alt_name' => $request->project_alt_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Lead creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available developers and their projects
     */
    public function getDevelopersAndProjects(): JsonResponse
    {
        try {
        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })
        ->with(['projects' => function($query) {
            $query->where('is_active', true);
        }])
        ->get()
        ->map(function($developer) {
            return [
                'alt_name' => $developer->email, // Use email as alt_name for API
                'name' => $developer->name,
                'projects' => $developer->projects->map(function($project) {
                    return [
                        'alt_name' => $project->alt_name,
                        'name' => $project->name,
                        'description' => $project->description
                    ];
                })
            ];
        });

            return response()->json([
                'success' => true,
                'data' => $developers
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch developers and projects', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch developers and projects'
            ], 500);
        }
    }

    /**
     * Assign lead to channel partner user using CP number-based assignment
     */
    private function assignLeadToChannelPartner(Lead $lead)
    {
        $project = $lead->project;
        $developer = $project->developer;
        
        if (!$developer) {
            Log::warning('No developer user found for project', [
                'lead_id' => $lead->id,
                'project_id' => $project->id
            ]);
            return;
        }
        
        // Get mapped channel partners for this developer, filtered and sorted by cp_number from users table
        $mappedChannelPartners = \App\Models\DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $developer->id)
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
                'developer_user_id' => $developer->id,
                'developer_name' => $developer->name,
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
                'developer_user_id' => $developer->id,
                'total_mapped_cps' => $mappedChannelPartners->count()
            ]);
            return;
        }

        // Assign the lead to the selected channel partner
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
            'developer_user_id' => $developer->id,
            'developer_name' => $developer->name,
            'project_id' => $project->id,
            'project_name' => $project->name,
            'last_assigned_cp_number' => $lastAssignedCpNumber,
            'assigned_cp_number' => $nextCpNumber,
            'mapped_cps_count' => $mappedChannelPartners->count()
        ]);
    }
}