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
        Schema::create('developer_user_channel_partner_user_mappings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('developer_user_id');
            $table->uuid('channel_partner_user_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('developer_user_id', 'dev_user_fk')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('channel_partner_user_id', 'cp_user_fk')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['developer_user_id', 'channel_partner_user_id'], 'dev_cp_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_user_channel_partner_user_mappings');
    }
};