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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id('clinic_id');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });

        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasTable('doctors')) {
                $table->foreign('clinic_id')->references('clinic_id')->on('clinics')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'clinic_id')) {
                $table->dropForeign(['clinic_id']);
            }
        });
        Schema::dropIfExists('clinics');
    }
};
