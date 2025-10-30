<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) Assign sample cp_number values to channel partner users who don't have one
        // Determine the next number after the current maximum
        $max = DB::table('users')->max('cp_number');
        $next = is_null($max) ? 1 : ((int) $max + 1);

        // Fetch channel partner users missing cp_number
        $missing = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'channel_partner')
            ->whereNull('users.cp_number')
            ->orderBy('users.created_at', 'asc')
            ->select('users.id')
            ->get();

        foreach ($missing as $row) {
            DB::table('users')->where('id', $row->id)->update(['cp_number' => $next]);
            $next++;
        }

        // 2) Ensure uniqueness at the DB level
        Schema::table('users', function (Blueprint $table) {
            // Add unique index only if it does not exist
            // Laravel doesn't support conditional index checks natively; wrap in try/catch
            try {
                $table->unique('cp_number', 'users_cp_number_unique');
            } catch (\Throwable $e) {
                // Index may already exist; ignore
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove unique index if present (we won't revert assigned numbers)
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('users_cp_number_unique');
            } catch (\Throwable $e) {
                // Ignore if it doesn't exist
            }
        });
    }
};


