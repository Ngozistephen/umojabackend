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
        Schema::create('local_pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained();
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
        Schema::dropIfExists('local_pickups');
    }
};
