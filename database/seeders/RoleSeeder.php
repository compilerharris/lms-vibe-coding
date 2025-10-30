<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full access',
            ],
            [
                'name' => 'leader',
                'description' => 'Leader with master table access',
            ],
            [
                'name' => 'site_head',
                'description' => 'Site Head (may be same as Leader)',
            ],
            [
                'name' => 'cs',
                'description' => 'Customer Service with read access',
            ],
            [
                'name' => 'biddable',
                'description' => 'Biddable with read access',
            ],
            [
                'name' => 'developer',
                'description' => 'Developer with read access to all projects',
            ],
            [
                'name' => 'channel_partner',
                'description' => 'Channel Partner with access to assigned projects',
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
