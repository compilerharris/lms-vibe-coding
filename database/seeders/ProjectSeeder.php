<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = \App\Models\Developer::first();
        
        if ($developer) {
            \App\Models\Project::firstOrCreate(
                ['name' => 'Sample Project'],
                [
                    'name' => 'Sample Project',
                    'description' => 'A sample project for testing the lead assignment system',
                    'developer_id' => $developer->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
