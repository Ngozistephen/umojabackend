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
            $table->enum('order_status', ['processed', 'shipped', 'intransit', 'delivered', 'processing', 'awaiting_shipment'])
                  ->default('processing')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', ['processed', 'shipped', 'intransit', 'delivered', 'processing'])
            ->default('processing')
            ->change();
        });
    }
};
