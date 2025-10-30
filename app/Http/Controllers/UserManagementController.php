<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['role'])->get();
        return view('user-management.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('user-management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|uuid|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return redirect()->route('user-management.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $user->load(['role']);
        return view('user-management.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user-management.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|uuid|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->route('user-management.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('user-management.index')
                ->with('error', 'You cannot delete your own account.');
        }

        try {
            // Check if user has assigned leads (for channel partners)
            if ($user->isChannelPartner()) {
                $assignedLeads = $user->assignedLeads()->count();
                if ($assignedLeads > 0) {
                    return redirect()->route('user-management.index')
                        ->with('error', 'Cannot delete channel partner with assigned leads. Please reassign leads first.');
                }
            }

            // Check if user has projects (for developers)
            if ($user->isDeveloper()) {
                $projects = $user->projects()->count();
                if ($projects > 0) {
                    return redirect()->route('user-management.index')
                        ->with('error', 'Cannot delete developer with active projects. Please reassign projects first.');
                }
            }

            // Note: We allow deletion even with active mappings since we have cascading deletes
            // The database will handle cleanup of mapping entries automatically

            $user->delete();
            return redirect()->route('user-management.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('User deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('user-management.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
