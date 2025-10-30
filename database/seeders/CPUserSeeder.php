<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\ChannelPartner;
use App\Models\Developer;
use Illuminate\Support\Facades\Hash;

class CPUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Channel Partner role if it doesn't exist
        $cpRole = Role::firstOrCreate(['name' => 'channel_partner'], ['description' => 'Channel Partner']);

        // Get the existing developer (we'll use the one created in DeveloperUserSeeder)
        $developer = Developer::where('email', 'developer@example.com')->first();
        
        if (!$developer) {
            $this->command->error('Developer not found. Please run DeveloperUserSeeder first.');
            return;
        }

        // Create Channel Partner record
        $channelPartner = ChannelPartner::where('email', 'cp@example.com')->first();
        if (!$channelPartner) {
            $channelPartner = ChannelPartner::create([
                'name' => 'Sample Channel Partner',
                'email' => 'cp@example.com',
                'phone' => '9876543210',
                'address' => '456 CP Street, City',
                'developer_id' => $developer->id,
                'is_active' => true,
                'round_robin_count' => 1,
            ]);
            $this->command->info('Channel Partner created successfully!');
        } else {
            // Update existing CP to ensure it's mapped to the developer
            $channelPartner->update([
                'developer_id' => $developer->id,
                'is_active' => true,
            ]);
            $this->command->info('Channel Partner updated successfully!');
        }

        // Create CP user
        $cpUser = User::where('email', 'cp@example.com')->first();
        if (!$cpUser) {
            User::create([
                'name' => 'CP User',
                'email' => 'cp@example.com',
                'password' => Hash::make('password'),
                'role_id' => $cpRole->id,
            ]);
            $this->command->info('CP user created successfully!');
            $this->command->info('Email: cp@example.com');
            $this->command->info('Password: password');
        } else {
            // Ensure the existing CP user has the correct role and password
            $cpUser->update([
                'role_id' => $cpRole->id,
                'password' => Hash::make('password'), // Reset password for consistency
            ]);
            $this->command->info('CP user already exists and was updated.');
            $this->command->info('Email: cp@example.com');
            $this->command->info('Password: password');
        }

        $this->command->info('CP user setup completed!');
        $this->command->info('Channel Partner is mapped to Developer: ' . $developer->name);
    }
}