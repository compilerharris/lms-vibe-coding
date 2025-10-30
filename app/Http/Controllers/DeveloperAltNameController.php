<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;

class DeveloperAltNameController extends Controller
{
    /**
     * Display the Developer and Project Alt Names page.
     */
    public function index()
    {
        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })->orderBy('name', 'asc')->get();
        
        $projects = Project::with('developer')->orderBy('name', 'asc')->get();
        
        return view('admin.developer-alt-names', compact('developers', 'projects'));
    }
}
