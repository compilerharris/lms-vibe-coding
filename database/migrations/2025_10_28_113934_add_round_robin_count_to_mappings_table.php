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
            $table->integer('round_robin_count')->default(1)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developer_user_channel_partner_user_mappings', function (Blueprint $table) {
            $table->dropColumn('round_robin_count');
        });
    }
};