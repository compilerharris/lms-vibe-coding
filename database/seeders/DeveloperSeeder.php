<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Developer::firstOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => 'Sample Developer',
                'email' => 'developer@example.com',
                'phone' => '+1234567890',
                'address' => '123 Developer Street, City, State',
                'is_active' => true,
            ]
        );
    }
}
