<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeveloperController extends Controller
{
    /**
     * Display a listing of developer users (read-only).
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isLeader()) {
            abort(403, 'Unauthorized access.');
        }

        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })->withCount(['projects'])->get();
        
        return view('developers.index', compact('developers'));
    }

    /**
     * Display the specified developer user (read-only).
     */
    public function show(User $developer)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isLeader()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this is a developer user
        if (!$developer->isDeveloper()) {
            abort(404, 'Developer not found.');
        }

        $developer->load(['projects']);
        return view('developers.show', compact('developer'));
    }

    /**
     * Redirect to user management for creating developers.
     */
    public function create()
    {
        return redirect()->route('user-management.create')
            ->with('info', 'Please create developer users through User Management.');
    }

    /**
     * Redirect to user management for editing developers.
     */
    public function edit(User $developer)
    {
        return redirect()->route('user-management.edit', $developer)
            ->with('info', 'Please edit developer users through User Management.');
    }

    /**
     * Redirect to user management for updating developers.
     */
    public function update(Request $request, User $developer)
    {
        return redirect()->route('user-management.edit', $developer)
            ->with('info', 'Please update developer users through User Management.');
    }

    /**
     * Redirect to user management for deleting developers.
     */
    public function destroy(User $developer)
    {
        return redirect()->route('user-management.index')
            ->with('info', 'Please delete developer users through User Management.');
    }
}