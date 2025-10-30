<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Resolve the channel_partner role id
        $cpRoleId = DB::table('roles')->where('name', 'channel_partner')->value('id');
        if (!$cpRoleId) {
            return; // nothing to do
        }

        // Determine starting number
        $max = DB::table('users')->max('cp_number');
        $next = is_null($max) ? 1 : ((int) $max + 1);

        // Get channel partner users with null cp_number
        $rows = DB::table('users')
            ->where('role_id', $cpRoleId)
            ->whereNull('cp_number')
            ->orderBy('created_at', 'asc')
            ->select('id')
            ->get();

        foreach ($rows as $row) {
            DB::table('users')->where('id', $row->id)->update(['cp_number' => $next]);
            $next++;
        }
    }

    public function down(): void
    {
        // Do not reset assigned numbers
    }
};


