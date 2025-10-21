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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                // If role column doesn't exist, create it
                $table->enum('role', ['admin', 'doctor', 'patient'])->default('patient')->after('email');
            } else {
                // If role column exists, modify it to ensure it has the correct enum values
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'doctor', 'patient') NOT NULL DEFAULT 'patient'");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
