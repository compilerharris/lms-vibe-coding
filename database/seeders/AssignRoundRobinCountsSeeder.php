<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeveloperUserChannelPartnerUserMapping;

class AssignRoundRobinCountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active mappings grouped by developer
        $mappingsByDeveloper = DeveloperUserChannelPartnerUserMapping::where('is_active', true)
            ->with(['developerUser', 'channelPartnerUser'])
            ->get()
            ->groupBy('developer_user_id');

        foreach ($mappingsByDeveloper as $developerId => $mappings) {
            $count = 1;
            foreach ($mappings as $mapping) {
                $mapping->update(['round_robin_count' => $count]);
                $this->command->info("Assigned round-robin count {$count} to mapping: {$mapping->developerUser->name} -> {$mapping->channelPartnerUser->name}");
                $count++;
            }
        }

        $this->command->info("Assigned round-robin counts to " . DeveloperUserChannelPartnerUserMapping::where('is_active', true)->count() . " mappings.");
    }
}