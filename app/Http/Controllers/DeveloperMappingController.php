<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DeveloperUserChannelPartnerUserMapping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeveloperMappingController extends Controller
{
    /**
     * Display the developer mapping management page.
     */
    public function index()
    {
        // Get all developer users
        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })->with(['mappedChannelPartners'])->get();

        // Get all channel partner users
        $channelPartners = User::whereHas('role', function($query) {
            $query->where('name', 'channel_partner');
        })->get();

        return view('admin.developer-mapping', compact('developers', 'channelPartners'));
    }

    /**
     * Store or update developer-channel partner mappings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'developer_user_id' => 'required|uuid|exists:users,id',
            'channel_partner_user_ids' => 'required|array',
            'channel_partner_user_ids.*' => 'uuid|exists:users,id',
        ]);

        DB::transaction(function () use ($request) {
            $developerUserId = $request->developer_user_id;
            $channelPartnerUserIds = $request->channel_partner_user_ids;

            // Check if any channel partners are already mapped to other developers
            $alreadyMappedCPs = DeveloperUserChannelPartnerUserMapping::where('is_active', true)
                ->whereIn('channel_partner_user_id', $channelPartnerUserIds)
                ->where('developer_user_id', '!=', $developerUserId)
                ->with('channelPartnerUser')
                ->get();

            if ($alreadyMappedCPs->count() > 0) {
                $mappedCPNames = $alreadyMappedCPs->pluck('channelPartnerUser.name')->join(', ');
                throw new \Exception("The following channel partners are already mapped to other developers: {$mappedCPNames}");
            }

            // First, deactivate all existing mappings for this developer
            DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $developerUserId)
                ->update(['is_active' => false]);

            // Then create/activate new mappings
            foreach ($channelPartnerUserIds as $channelPartnerUserId) {
                DeveloperUserChannelPartnerUserMapping::updateOrCreate(
                    [
                        'developer_user_id' => $developerUserId,
                        'channel_partner_user_id' => $channelPartnerUserId,
                    ],
                    [
                        'is_active' => true,
                    ]
                );
            }
        });

        return redirect()->route('developer-mapping.index')
            ->with('success', 'Developer-Channel Partner mappings updated successfully!');
    }

    /**
     * Get available channel partners for a developer.
     * Includes CPs already mapped to THIS developer, but excludes those mapped to other developers.
     */
    public function getAvailableChannelPartners(Request $request)
    {
        $developerUserId = $request->developer_user_id;

        // All CP users
        $allChannelPartners = User::whereHas('role', function($query) {
            $query->where('name', 'channel_partner');
        })->get();

        // CPs mapped to other developers (active)
        $mappedToOthers = DeveloperUserChannelPartnerUserMapping::where('is_active', true)
            ->when($developerUserId, function($q) use ($developerUserId) {
                $q->where('developer_user_id', '!=', $developerUserId);
            })
            ->pluck('channel_partner_user_id')
            ->toArray();

        // Available = all CPs except those mapped to other developers
        $available = $allChannelPartners->filter(function($cp) use ($mappedToOthers) {
            return !in_array($cp->id, $mappedToOthers);
        })->values();

        return response()->json($available);
    }

    /**
     * Get ids of channel partners currently mapped to a developer (active).
     */
    public function getMappedChannelPartners(Request $request)
    {
        $developerUserId = $request->developer_user_id;
        if (!$developerUserId) {
            return response()->json([]);
        }

        $ids = DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $developerUserId)
            ->where('is_active', true)
            ->pluck('channel_partner_user_id')
            ->toArray();

        return response()->json($ids);
    }

    /**
     * Remove a specific mapping.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'developer_user_id' => 'required|uuid|exists:users,id',
            'channel_partner_user_id' => 'required|uuid|exists:users,id',
        ]);

        DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $request->developer_user_id)
            ->where('channel_partner_user_id', $request->channel_partner_user_id)
            ->update(['is_active' => false]);

        return redirect()->route('developer-mapping.index')
            ->with('success', 'Mapping removed successfully!');
    }
}