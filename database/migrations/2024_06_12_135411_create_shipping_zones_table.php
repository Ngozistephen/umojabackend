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
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('vendor_id')->nullable()->constrained();
            $table->foreignId('shipping_method_id')->constrained('shipping_methods');
            $table->string('name')->nullable();
            $table->string('contient')->nullable();
            $table->json('countries')->nullable();
            $table->string('local_delivery_company')->nullable();
            $table->string('local_delivery_address')->nullable();
            $table->string('local_delivery_country_name')->nullable();
            $table->string('local_delivery_city')->nullable();
            $table->string('local_delivery_state')->nullable();
            $table->string('local_delivery_apartment')->nullable();
            $table->string('local_delivery_zipcode')->nullable();
            $table->string('local_delivery_phone_number')->unique()->nullable();
            $table->string('local_pickup_company')->nullable();
            $table->string('local_pickup_address')->nullable();
            $table->string('local_pickup_country_name')->nullable();
            $table->string('local_pickup_city')->nullable();
            $table->string('local_pickup_state')->nullable();
            $table->string('local_pickup_apartment')->nullable();
            $table->string('local_pickup_zipcode')->nullable();
            $table->string('local_pickup_phone_number')->unique()->nullable();         
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
