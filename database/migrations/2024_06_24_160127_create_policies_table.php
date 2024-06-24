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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->string('14_days')->nullable();
            $table->string('30_days')->nullable();
            $table->string('90_days')->nullable();
            $table->string('unlimited')->nullable();
            $table->string('custom_days')->nullable();
            $table->string('customer_provides_return_shipping')->nullable();
            $table->string('free_return_shipping')->nullable();
            $table->string('flat_rate_return_shipping')->nullable();
            $table->string('no_refund')->nullable();
            $table->string('full_refund')->nullable();
            $table->string('50%_refund')->nullable();
            $table->boolean('restocking_fee')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
