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
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
            $table->string('duration')->nullable()->change();
            $table->unsignedInteger('amount')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
            $table->string('duration')->nullable(false)->change();
            $table->unsignedInteger('amount')->nullable(false)->default(null)->change();
        });
    }
};
