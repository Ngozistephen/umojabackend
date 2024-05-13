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
        Schema::table('vendors', function (Blueprint $table) {
            $table->text('business_profile_photo')->nullable();
            $table->string('office_country')->nullable();
            $table->string('office_state')->nullable();
            $table->string('office_city')->nullable();
            $table->string('office_address')->nullable();
            $table->string('complex_building_address')->nullable();
            $table->string('busniess_email');
            $table->string('busniess_phone_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
};
