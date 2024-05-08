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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('shipping_address_id')->constrained('shipping_addresses');
            $table->foreignId('shipping_method_id')->constrained('shipping_methods');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->foreignId('discount_code')->nullable()->constrained('discount_codes');
            $table->integer('order_number');
            $table->enum('fulfillment_status', ['fulfilled', 'unfulfilled', 'cancelled'])->default('unfulfilled');
            $table->unsignedInteger('sub_total')->default(0);
            $table->unsignedInteger('total_amount')->default(0);
            $table->unsignedInteger('delivery_charge')->default(0)->nullable();
            $table->enum('payment_status', ['paid', 'pending'])->default('pending');
            $table->enum('order_status', ['processed', 'shipped', 'intransit', 'delivered', 'processing', 'awaiting_shipment'])
                    ->default('processing');
            $table->string('transaction_id')->nullable();
            $table->string('tracking_number');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamp('cancelled_at')->nullable();
   	        $table->softDeletes(); 
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
