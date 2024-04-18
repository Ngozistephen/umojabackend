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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('sku')->nullable(); 
            $table->float('unit');
            $table->string('material')->nullable();
            $table->string('condition')->nullable();
            $table->boolean('sell_online')->default(true);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('product_spec')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories');
            $table->string('gender')->default('unisex')->nullable();
            $table->string('photo')->nullable();
            $table->string('slug');
            $table->string('ust_index')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('commission')->default(0);
            $table->unsignedInteger('compare_at_price')->default(0)->nullable();
            $table->boolean('tax_charge_on_product')->default(true);
            $table->unsignedInteger('cost_per_item')->default(0);
            $table->unsignedInteger('profit')->default(0);
            $table->unsignedInteger('margin')->default(0);
            $table->unsignedInteger('sales_count')->default(0);
            $table->boolean('track_quantity')->default(true);
            $table->integer('made_with_ghana_leather')->default(0);
            $table->integer('mini_stock')->default(0);
            $table->boolean('sell_out_of_stock')->default(false);
            $table->boolean('has_sku')->default(false);
            $table->string('storage_location')->nullable();
            $table->boolean('product_ship_internationally')->default(false);
             $table->unsignedInteger('gross_weight')->default(0);
            $table->unsignedInteger('net_weight')->default(0);
            $table->unsignedInteger('length')->default(0);
            $table->unsignedInteger('weight')->default(0);
            $table->unsignedInteger('height')->default(0);
            $table->string('shipping_method')->nullable();   
            $table->boolean('digital_product_or_service')->default(false);
            $table->softDeletes();
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
