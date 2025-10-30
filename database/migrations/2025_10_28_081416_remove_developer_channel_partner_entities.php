<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update leads table to reference users instead of channel_partners
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['channel_partner_id']);
            $table->dropColumn('channel_partner_id');
            $table->uuid('assigned_user_id')->nullable()->after('project_id');
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Update projects table to reference users instead of developers
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['developer_id']);
            $table->dropColumn('developer_id');
            $table->uuid('developer_user_id')->nullable()->after('name');
            $table->foreign('developer_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Update users table to remove developer_id reference
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['developer_id']);
            $table->dropColumn('developer_id');
        });

        // Drop the developer_channel_partner_mappings table
        Schema::dropIfExists('developer_channel_partner_mappings');

        // Drop the channel_partners table
        Schema::dropIfExists('channel_partners');

        // Drop the developers table
        Schema::dropIfExists('developers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate developers table
        Schema::create('developers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('alt_name', 20)->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Recreate channel_partners table
        Schema::create('channel_partners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->uuid('developer_id');
            $table->boolean('is_active')->default(true);
            $table->integer('round_robin_count')->unique();
            $table->timestamps();

            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
        });

        // Recreate developer_channel_partner_mappings table
        Schema::create('developer_channel_partner_mappings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('developer_id');
            $table->uuid('channel_partner_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
            $table->foreign('channel_partner_id')->references('id')->on('channel_partners')->onDelete('cascade');

            $table->unique(['developer_id', 'channel_partner_id'], 'dev_cp_unique');
        });

        // Add developer_id back to users table
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('developer_id')->nullable()->after('role_id');
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('set null');
        });

        // Update projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['developer_user_id']);
            $table->dropColumn('developer_user_id');
            $table->uuid('developer_id')->nullable()->after('name');
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('set null');
        });

        // Update leads table
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->dropColumn('assigned_user_id');
            $table->uuid('channel_partner_id')->nullable()->after('project_id');
            $table->foreign('channel_partner_id')->references('id')->on('channel_partners')->onDelete('set null');
        });
    }
};