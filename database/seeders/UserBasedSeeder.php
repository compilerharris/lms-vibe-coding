<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use Illuminate\Support\Facades\Hash;

class UserBasedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $developerRole = Role::where('name', 'developer')->first();
        $channelPartnerRole = Role::where('name', 'channel_partner')->first();
        $csRole = Role::where('name', 'cs')->first();
        $biddableRole = Role::where('name', 'biddable')->first();

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@leadassignment.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'phone' => '+1234567890',
                'address' => 'Admin Office, Main Street',
            ]
        );

        // Create Developer Users
        $developer1 = User::firstOrCreate(
            ['email' => 'developer1@example.com'],
            [
                'name' => 'John Developer',
                'password' => Hash::make('password'),
                'role_id' => $developerRole->id,
                'phone' => '+1234567891',
                'address' => 'Developer Office 1, Tech Street',
            ]
        );

        $developer2 = User::firstOrCreate(
            ['email' => 'developer2@example.com'],
            [
                'name' => 'Jane Developer',
                'password' => Hash::make('password'),
                'role_id' => $developerRole->id,
                'phone' => '+1234567892',
                'address' => 'Developer Office 2, Innovation Avenue',
            ]
        );

        // Create Channel Partner Users
        $cp1 = User::firstOrCreate(
            ['email' => 'cp1@example.com'],
            [
                'name' => 'ABC Real Estate',
                'password' => Hash::make('password'),
                'role_id' => $channelPartnerRole->id,
                'phone' => '+1234567893',
                'address' => 'ABC Office, Real Estate District',
            ]
        );

        $cp2 = User::firstOrCreate(
            ['email' => 'cp2@example.com'],
            [
                'name' => 'XYZ Properties',
                'password' => Hash::make('password'),
                'role_id' => $channelPartnerRole->id,
                'phone' => '+1234567894',
                'address' => 'XYZ Office, Property Lane',
            ]
        );

        $cp3 = User::firstOrCreate(
            ['email' => 'cp3@example.com'],
            [
                'name' => 'Prime Realty',
                'password' => Hash::make('password'),
                'role_id' => $channelPartnerRole->id,
                'phone' => '+1234567895',
                'address' => 'Prime Office, Luxury Boulevard',
            ]
        );

        // Create CS User
        User::firstOrCreate(
            ['email' => 'cs@example.com'],
            [
                'name' => 'Customer Service',
                'password' => Hash::make('password'),
                'role_id' => $csRole->id,
                'phone' => '+1234567896',
                'address' => 'CS Office, Support Center',
            ]
        );

        // Create Biddable User
        User::firstOrCreate(
            ['email' => 'biddable@example.com'],
            [
                'name' => 'Biddable User',
                'password' => Hash::make('password'),
                'role_id' => $biddableRole->id,
                'phone' => '+1234567897',
                'address' => 'Biddable Office, Sales Center',
            ]
        );

        // Create Projects for Developer 1
        Project::firstOrCreate(
            ['name' => 'Sunrise Apartments'],
            [
                'alt_name' => 'SUNRISE123',
                'description' => 'Luxury apartments with modern amenities',
                'developer_user_id' => $developer1->id,
                'is_active' => true,
            ]
        );

        Project::firstOrCreate(
            ['name' => 'Garden Villas'],
            [
                'alt_name' => 'GARDEN456',
                'description' => 'Spacious villas with private gardens',
                'developer_user_id' => $developer1->id,
                'is_active' => true,
            ]
        );

        // Create Projects for Developer 2
        Project::firstOrCreate(
            ['name' => 'Tech Towers'],
            [
                'alt_name' => 'TECH789',
                'description' => 'Modern office towers in tech district',
                'developer_user_id' => $developer2->id,
                'is_active' => true,
            ]
        );

        Project::firstOrCreate(
            ['name' => 'Urban Lofts'],
            [
                'alt_name' => 'URBAN012',
                'description' => 'Contemporary lofts in city center',
                'developer_user_id' => $developer2->id,
                'is_active' => true,
            ]
        );

        $this->command->info('User-based data seeded successfully!');
        $this->command->info('Admin: admin@leadassignment.com / password');
        $this->command->info('Developer 1: developer1@example.com / password');
        $this->command->info('Developer 2: developer2@example.com / password');
        $this->command->info('Channel Partner 1: cp1@example.com / password');
        $this->command->info('Channel Partner 2: cp2@example.com / password');
        $this->command->info('Channel Partner 3: cp3@example.com / password');
        $this->command->info('CS: cs@example.com / password');
        $this->command->info('Biddable: biddable@example.com / password');
    }
}