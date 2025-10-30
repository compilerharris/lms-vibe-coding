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
        // Drop all tables in correct order (respecting foreign key constraints)
        Schema::dropIfExists('leads');
        Schema::dropIfExists('channel_partners');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
        Schema::dropIfExists('developers');
        Schema::dropIfExists('roles');

        // Recreate roles table with UUID
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Recreate developers table with UUID
        Schema::create('developers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Recreate users table with UUID
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->uuid('role_id')->nullable();
            $table->uuid('developer_id')->nullable();
            
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
        });

        // Recreate projects table with UUID
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->uuid('developer_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
        });

        // Recreate channel_partners table with UUID
        Schema::create('channel_partners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->uuid('developer_id');
            $table->boolean('is_active')->default(true);
            $table->integer('round_robin_count')->default(1);
            $table->timestamps();
            
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
        });

        // Recreate leads table with UUID
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->uuid('project_id');
            $table->uuid('channel_partner_id')->nullable();
            $table->enum('status', ['new', 'assigned', 'contacted', 'converted', 'lost'])->default('new');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('channel_partner_id')->references('id')->on('channel_partners')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all tables
        Schema::dropIfExists('leads');
        Schema::dropIfExists('channel_partners');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
        Schema::dropIfExists('developers');
        Schema::dropIfExists('roles');

        // Recreate with original auto-increment structure
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('developer_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('developer_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('channel_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('developer_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->integer('round_robin_count')->default(1);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('channel_partner_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['new', 'assigned', 'contacted', 'converted', 'lost'])->default('new');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
        });
    }
};