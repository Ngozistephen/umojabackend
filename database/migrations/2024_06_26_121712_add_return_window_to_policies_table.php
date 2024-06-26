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
        if (!Schema::hasColumn('policies', 'return_window', 'return_shipping_cost','refund_policy')) {
            Schema::table('policies', function (Blueprint $table) {
                $table->string('return_window');
                $table->string('return_shipping_cost');
                $table->string('refund_policy');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            //
        });
    }
};
