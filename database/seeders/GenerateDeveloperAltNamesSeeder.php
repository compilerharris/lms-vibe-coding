<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class GenerateDeveloperAltNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all developer users without alt_name
        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })->whereNull('alt_name')->get();

        foreach ($developers as $developer) {
            $developer->alt_name = $developer->generateAltName();
            $developer->save();
            
            $this->command->info("Generated alt_name '{$developer->alt_name}' for developer '{$developer->name}'");
        }

        $this->command->info("Generated alt_names for {$developers->count()} developer users.");
    }
}