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
            $table->text('business_image')->nullable()->change();
            $table->text('utility_photo')->nullable()->change();
            $table->text('business_number_photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('business_image')->nullable()->change();
            $table->string('utility_photo')->nullable()->change();
            $table->string('business_number_photo')->nullable()->change();
        });
    }
};
