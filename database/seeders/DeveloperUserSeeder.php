<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Developer;

class DeveloperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get developer role
        $developerRole = Role::where('name', 'developer')->first();
        
        if (!$developerRole) {
            $this->command->error('Developer role not found. Please run RoleSeeder first.');
            return;
        }

        // Get or create a developer
        $developer = Developer::first();
        if (!$developer) {
            $developer = Developer::create([
                'name' => 'Test Real Estate Developer',
                'email' => 'developer@test.com',
                'phone' => '+1234567890',
                'address' => '123 Developer Street, City, State',
                'is_active' => true,
            ]);
        }

        // Create developer user
        $user = User::where('email', 'developer@example.com')->first();
        if (!$user) {
            User::create([
                'name' => 'Developer User',
                'email' => 'developer@example.com',
                'password' => bcrypt('password'),
                'role_id' => $developerRole->id,
                'developer_id' => $developer->id,
            ]);
            
            $this->command->info('Developer user created successfully!');
            $this->command->info('Email: developer@example.com');
            $this->command->info('Password: password');
        } else {
            $this->command->info('Developer user already exists.');
        }
    }
}
