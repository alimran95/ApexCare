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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->nullable()->after('phone');
            }
        });

        // Backfill new role column from existing user_type values
        try {
            DB::statement("UPDATE `users` SET `role` = CASE WHEN `user_type`='admin' THEN 'super admin' ELSE `user_type` END WHERE `role` IS NULL OR `role`=''");
        } catch (\Throwable $e) {}

        // Make role required with default patient
        try {
            DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(20) NOT NULL DEFAULT 'patient'");
        } catch (\Throwable $e) {}

        // Drop old user_type enum column if it exists
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate user_type as VARCHAR and backfill from role
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type', 20)->nullable()->after('phone');
            }
        });
        try {
            DB::statement("UPDATE `users` SET `user_type` = CASE WHEN `role`='super admin' THEN 'admin' ELSE `role` END");
            DB::statement("ALTER TABLE `users` MODIFY `user_type` VARCHAR(20) NOT NULL DEFAULT 'patient'");
        } catch (\Throwable $e) {}

        // Drop role column
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
