<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get projects based on user role
        if ($user->isAdmin() || $user->isLeader()) {
            $projects = Project::with('developer')
                ->withCount('leads')
                ->paginate(10);
        } elseif ($user->isDeveloper()) {
            $projects = Project::where('developer_user_id', $user->id)
                ->with('developer')
                ->withCount('leads')
                ->paginate(10);
        } else {
            $projects = collect();
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })->get();
        return view('projects.create', compact('developers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'developer_user_id' => 'required|uuid|exists:users,id',
        ]);

        try {
            Project::create($request->all());
            return redirect()->route('projects.index')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load('developer', 'leads.assignedUser');
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })->get();
        return view('projects.edit', compact('project', 'developers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'description' => 'nullable|string',
            'developer_user_id' => 'required|uuid|exists:users,id',
        ]);

        try {
            $project->update($request->all());
            return redirect()->route('projects.index')
                ->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update project: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('projects.index')
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete project: ' . $e->getMessage());
        }
    }
}