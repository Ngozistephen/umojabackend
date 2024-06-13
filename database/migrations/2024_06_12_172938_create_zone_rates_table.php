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
        if (!schema::hasTable('zone_rates') ){

            Schema::create('zone_rates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('shipping_zone_id')->constrained('shipping_zones');
                $table->string('name')->nullable();
                $table->string('custom_rate_name')->nullable();
                $table->string('condition')->nullable();
                $table->text('custom_delivery_description')->nullable();
                $table->unsignedInteger('price')->default(0);
                $table->boolean('based_on_item_weight')->default(false)->nullable();
                $table->boolean('based_on_order_price')->default(false)->nullable();
                $table->integer('minimum_weight')->default(0)->nullable();
                $table->integer('maximum_weight')->default(0)->nullable();      
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
