<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('channel_partners', function (Blueprint $table) {
            // Add unique constraints
            $table->unique('name', 'channel_partners_name_unique');
            $table->unique('phone', 'channel_partners_phone_unique');
            $table->unique('round_robin_count', 'channel_partners_round_robin_count_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_partners', function (Blueprint $table) {
            // Drop unique constraints
            $table->dropUnique('channel_partners_name_unique');
            $table->dropUnique('channel_partners_phone_unique');
            $table->dropUnique('channel_partners_round_robin_count_unique');
        });
    }
};
