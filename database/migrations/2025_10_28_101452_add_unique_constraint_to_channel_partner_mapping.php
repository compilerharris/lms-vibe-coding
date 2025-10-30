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
        Schema::table('developer_user_channel_partner_user_mappings', function (Blueprint $table) {
            // Add unique constraint to ensure one channel partner can only be mapped to one developer
            $table->unique('channel_partner_user_id', 'unique_channel_partner_mapping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developer_user_channel_partner_user_mappings', function (Blueprint $table) {
            $table->dropUnique('unique_channel_partner_mapping');
        });
    }
};