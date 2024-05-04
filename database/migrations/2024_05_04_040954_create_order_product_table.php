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
        if (!schema::hasTable('order_product') ){

            Schema::create('order_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products');
                $table->foreignId('order_id')->constrained('orders');
                $table->foreignId('vendor_id')->constrained('vendors');
                $table->string('qty');
                $table->string('tracking_id');
                $table->unsignedInteger('price');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_order');
    }
};
