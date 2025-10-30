<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add alt_name column to developers table (nullable initially)
        Schema::table('developers', function (Blueprint $table) {
            $table->string('alt_name', 20)->nullable()->after('name');
        });

        // Add alt_name column to projects table (nullable initially)
        Schema::table('projects', function (Blueprint $table) {
            $table->string('alt_name', 20)->nullable()->after('name');
        });

        // Populate alt_name for existing developers
        $developers = \App\Models\Developer::all();
        foreach ($developers as $developer) {
            $developer->alt_name = $developer->generateAltName();
            $developer->save();
        }

        // Populate alt_name for existing projects
        $projects = \App\Models\Project::all();
        foreach ($projects as $project) {
            $project->alt_name = $project->generateAltName();
            $project->save();
        }

        // Make alt_name columns unique and not null
        Schema::table('developers', function (Blueprint $table) {
            $table->string('alt_name', 20)->unique()->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('alt_name', 20)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('alt_name');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('alt_name');
        });
    }
};