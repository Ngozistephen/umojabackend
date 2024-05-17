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
        

        if (!Schema::hasColumn('orders', 'discount_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('discount_code')->nullable()->default(NULL)->constrained('discount_codes');
            });
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('discount_code')->nullable()->default(NULL)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['discount_code']);
            $table->dropColumn('discount_code');
        });
    }
};
