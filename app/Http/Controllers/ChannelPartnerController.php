<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelPartnerController extends Controller
{
    /**
     * Display a listing of channel partner users (read-only).
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isLeader()) {
            abort(403, 'Unauthorized access.');
        }

        $channelPartners = User::whereHas('role', function($query) {
            $query->where('name', 'channel_partner');
        })->withCount(['assignedLeads'])->get();
        
        return view('channel-partners.index', compact('channelPartners'));
    }

    /**
     * Display the specified channel partner user (read-only).
     */
    public function show(User $channelPartner)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isLeader()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this is a channel partner user
        if (!$channelPartner->isChannelPartner()) {
            abort(404, 'Channel Partner not found.');
        }

        $channelPartner->load(['assignedLeads.project']);
        return view('channel-partners.show', compact('channelPartner'));
    }

    /**
     * Redirect to user management for creating channel partners.
     */
    public function create()
    {
        return redirect()->route('user-management.create')
            ->with('info', 'Please create channel partner users through User Management.');
    }

    /**
     * Redirect to user management for editing channel partners.
     */
    public function edit(User $channelPartner)
    {
        return redirect()->route('user-management.edit', $channelPartner)
            ->with('info', 'Please edit channel partner users through User Management.');
    }

    /**
     * Redirect to user management for updating channel partners.
     */
    public function update(Request $request, User $channelPartner)
    {
        return redirect()->route('user-management.edit', $channelPartner)
            ->with('info', 'Please update channel partner users through User Management.');
    }

    /**
     * Redirect to user management for deleting channel partners.
     */
    public function destroy(User $channelPartner)
    {
        return redirect()->route('user-management.index')
            ->with('info', 'Please delete channel partner users through User Management.');
    }
}