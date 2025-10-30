<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CSBiddableUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create CS role if it doesn't exist
        $csRole = Role::firstOrCreate(['name' => 'cs'], ['description' => 'Customer Service']);

        // Create Biddable role if it doesn't exist
        $biddableRole = Role::firstOrCreate(['name' => 'biddable'], ['description' => 'Biddable']);

        // Create CS user
        $csUser = User::where('email', 'cs@example.com')->first();
        if (!$csUser) {
            User::create([
                'name' => 'CS User',
                'email' => 'cs@example.com',
                'password' => Hash::make('password'),
                'role_id' => $csRole->id,
            ]);
            $this->command->info('CS user created successfully!');
            $this->command->info('Email: cs@example.com');
            $this->command->info('Password: password');
        } else {
            // Ensure the existing CS user has the correct role and password
            $csUser->update([
                'role_id' => $csRole->id,
                'password' => Hash::make('password'), // Reset password for consistency
            ]);
            $this->command->info('CS user already exists and was updated.');
            $this->command->info('Email: cs@example.com');
            $this->command->info('Password: password');
        }

        // Create Biddable user
        $biddableUser = User::where('email', 'biddable@example.com')->first();
        if (!$biddableUser) {
            User::create([
                'name' => 'Biddable User',
                'email' => 'biddable@example.com',
                'password' => Hash::make('password'),
                'role_id' => $biddableRole->id,
            ]);
            $this->command->info('Biddable user created successfully!');
            $this->command->info('Email: biddable@example.com');
            $this->command->info('Password: password');
        } else {
            // Ensure the existing Biddable user has the correct role and password
            $biddableUser->update([
                'role_id' => $biddableRole->id,
                'password' => Hash::make('password'), // Reset password for consistency
            ]);
            $this->command->info('Biddable user already exists and was updated.');
            $this->command->info('Email: biddable@example.com');
            $this->command->info('Password: password');
        }
    }
}