<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelPartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developers = \App\Models\Developer::all();
        
        if ($developers->count() > 0) {
            $developer = $developers->first();
            
            // Create sample channel partners
            $channelPartners = [
                [
                    'name' => 'ABC Real Estate',
                    'email' => 'abc@realestate.com',
                    'phone' => '+1234567891',
                    'address' => '456 Partner Avenue, City, State',
                    'developer_id' => $developer->id,
                    'is_active' => true,
                ],
                [
                    'name' => 'XYZ Properties',
                    'email' => 'xyz@properties.com',
                    'phone' => '+1234567892',
                    'address' => '789 Property Lane, City, State',
                    'developer_id' => $developer->id,
                    'is_active' => true,
                ],
                [
                    'name' => 'Prime Realty',
                    'email' => 'prime@realty.com',
                    'phone' => '+1234567893',
                    'address' => '321 Prime Street, City, State',
                    'developer_id' => $developer->id,
                    'is_active' => true,
                ],
            ];

            foreach ($channelPartners as $index => $channelPartner) {
                $existingCP = \App\Models\ChannelPartner::where('email', $channelPartner['email'])->first();
                
                if (!$existingCP) {
                    // Get the next available round_robin_count
                    $maxCount = \App\Models\ChannelPartner::where('developer_id', $developer->id)->max('round_robin_count') ?? 0;
                    $channelPartner['round_robin_count'] = $maxCount + 1;
                    
                    \App\Models\ChannelPartner::create($channelPartner);
                }
            }
        }
    }
}
