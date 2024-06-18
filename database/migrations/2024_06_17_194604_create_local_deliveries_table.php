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
        Schema::create('local_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained();
            $table->string('local_delivery_company')->nullable();
            $table->string('local_delivery_address')->nullable();
            $table->string('local_delivery_country_name')->nullable();
            $table->string('local_delivery_city')->nullable();
            $table->string('local_delivery_state')->nullable();
            $table->string('local_delivery_apartment')->nullable();
            $table->string('local_delivery_zipcode')->nullable();
            $table->string('local_delivery_phone_number')->unique()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_delivery');
    }
};
