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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('transaction_id')->nullable();
            $table->integer('last_card_digits')->nullable();
            $table->string('last_card_brand')->nullable();
            $table->unsignedInteger('expiry_month')->nullable();
            $table->unsignedInteger('expiry_year')->nullable();
            
        });
    }

/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
            $table->dropColumn('last_card_digits');
            $table->dropColumn('last_card_brand');
            $table->dropColumn('expiry_month');
            $table->dropColumn('expiry_year');
        });
    }
};
