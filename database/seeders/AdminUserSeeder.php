<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Developer;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin role
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Get or create a developer
        $developer = Developer::first();
        if (!$developer) {
            $developer = Developer::create([
                'name' => 'Admin Developer',
                'email' => 'admin@developer.com',
                'phone' => '+1234567890',
                'address' => '123 Admin Street, City, State',
                'is_active' => true,
            ]);
        }

        // Create admin user
        $user = User::where('email', 'admin@example.com')->first();
        if (!$user) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role_id' => $adminRole->id,
                'developer_id' => $developer->id,
            ]);
            
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: password');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
