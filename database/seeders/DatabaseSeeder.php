<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DeveloperSeeder::class,
            ProjectSeeder::class,
            ChannelPartnerSeeder::class,
        ]);

        // Create admin user
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $developer = \App\Models\Developer::first();
        
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role_id' => $adminRole->id,
                'developer_id' => $developer->id,
            ]
        );

        // Create CS user
        $csRole = \App\Models\Role::where('name', 'cs')->first();
        User::firstOrCreate(
            ['email' => 'cs@example.com'],
            [
                'name' => 'CS User',
                'email' => 'cs@example.com',
                'password' => bcrypt('password'),
                'role_id' => $csRole->id,
                'developer_id' => $developer->id,
            ]
        );

        // Create Biddable user
        $biddableRole = \App\Models\Role::where('name', 'biddable')->first();
        User::firstOrCreate(
            ['email' => 'biddable@example.com'],
            [
                'name' => 'Biddable User',
                'email' => 'biddable@example.com',
                'password' => bcrypt('password'),
                'role_id' => $biddableRole->id,
                'developer_id' => $developer->id,
            ]
        );

        // Create Developer user
        $developerRole = \App\Models\Role::where('name', 'developer')->first();
        User::firstOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => 'Developer User',
                'email' => 'developer@example.com',
                'password' => bcrypt('password'),
                'role_id' => $developerRole->id,
                'developer_id' => $developer->id,
            ]
        );

        // Create Channel Partner users
        $channelPartnerRole = \App\Models\Role::where('name', 'channel_partner')->first();
        $channelPartners = \App\Models\ChannelPartner::all();
        
        foreach ($channelPartners as $index => $channelPartner) {
            $email = 'user@' . strtolower(str_replace(' ', '', $channelPartner->name)) . '.com';
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $channelPartner->name . ' User',
                    'email' => $email,
                    'password' => bcrypt('password'),
                    'role_id' => $channelPartnerRole->id,
                    'developer_id' => $channelPartner->developer_id,
                ]
            );
        }
    }
}
