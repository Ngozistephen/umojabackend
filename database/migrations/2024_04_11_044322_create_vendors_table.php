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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('rep_country')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_website')->nullable();
            $table->string('business_image')->nullable();
            $table->string('address')->nullable();
            $table->string('country_name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            // $table->string('first_name');
            // $table->string('last_name');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
             $table->string('gender')->nullable();
            $table->date('date_birth')->nullable();
            $table->string('vendor_id_form_type')->nullable();	
            $table->string('vendor_id_number')->nullable();	
            $table->string('picture_vendor_id_number')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('business_number')->nullable();
            $table->string('tax_id_number')->nullable();
            $table->string('utility_bill_type')->nullable();
            $table->string('utility_photo')->nullable();
            $table->string('business_number_photo')->nullable();
            // $table->string('password')->nullable();
            // $table->string('status')->default('active');
            $table->string('language')->default('English')->nullable();
            $table->boolean('verified')->default(false);
            // $table->string('profile_photo')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('facebook_handle')->nullable();
            $table->string('instagram_handle')->nullable();
            $table->string('youtube_handle')->nullable();
            $table->string('building_name')->nullable();
            $table->text('business_bio')->nullable();
            $table->boolean('accept_mail_marketing')->default(false);
            // $table->string('zipcode')->nullable();
            // $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('tax_exempt')->default(false);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('name_on_account')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('iban')->nullable(); 
            $table->string('cover_image')->nullable(); 
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
