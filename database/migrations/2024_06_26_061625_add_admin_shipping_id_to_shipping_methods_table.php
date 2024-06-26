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
        if (!Schema::hasColumn('shipping_methods', 'admin_shipping_id')) {

            Schema::table('shipping_methods', function (Blueprint $table) {
                $table->foreignId('admin_shipping_id')->constrained('admin_shippings');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            //
        });
    }
};
