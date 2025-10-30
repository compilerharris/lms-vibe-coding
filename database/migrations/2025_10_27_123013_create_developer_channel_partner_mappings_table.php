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
        Schema::create('developer_channel_partner_mappings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('developer_id');
            $table->uuid('channel_partner_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
            $table->foreign('channel_partner_id')->references('id')->on('channel_partners')->onDelete('cascade');

            // Unique constraint to prevent duplicate mappings
            $table->unique(['developer_id', 'channel_partner_id'], 'dev_cp_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_channel_partner_mappings');
    }
};