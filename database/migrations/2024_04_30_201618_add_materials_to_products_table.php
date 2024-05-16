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
        Schema::table('products', function (Blueprint $table) {
            $table->json('sizes')->nullable();
            $table->json('colors')->nullable();
            $table->json('materials')->nullable();
            $table->json('styles')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sizes');
            $table->dropColumn('colors');
            $table->dropColumn('materials');
            $table->dropColumn('styles');
        });
    }
};
