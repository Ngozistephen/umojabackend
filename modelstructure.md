# Laravel Schema for Tables

## 1. User Table

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('first_name');
    $table->string('last_name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->boolean('terms_accepted')->default(false);
    $table->string('status')->default('active');
    $table->string('phone_number')->unique()->nullable();
    $table->string('image')->default('https://ih0.redbubble.net/image.210602545.3386/flat,1000x1000,075,f.u1.jpg');
    $table->foreignId('role_id')->nullable()->constrained();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes(); 
   
    

    
    
   
//add role_id later
  
    
});






## 3. Subscription_package Table

```php
Schema::create('subscription_packages', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->unsignedInteger('amount')->default(0);
    $table->foreignId('vendor_id')->constrained('vendors');
    $table->string('status')->default('paid');
    $table->timestamps();
   
    
});


## 4. Vendor

```php
Schema::create('vendors', function (Blueprint $table) {
    $table->id();
    $table->string('rep_country')->nullable();
    $table->string('business_type')->nullable();
    $table->string('business_name')->nullable();
    $table->string('business_website')->nullable();
    $table->string('business_image')->nullable();
    $table->string('address')->nullable();
    $table->string('country_name')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('first_name');
    $table->string('last_name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
     $table->string('gender')->nullable();
    $table->date('date_birth')->nullable();
    $table->string('vendor_id_form_type')->nullable();	
    $table->string('vendor_id_number')->nullable();	
    $table->string('picture_vendor_id_number')->nullable();
    $table->string('phone_number')->unique()->nullable();
    $table->string('business_number')->nullable();
    $table->string('tax_id_number')->nullable();
    $table->foreignId('user_id')->constrained('users');
    $table->string('utility_bill_type')->nullable();
    $table->string('utility_photo')->nullable();
    $table->string('business_number_photo')->nullable();
    $table->string('password')->nullable();
    $table->string('image')->default('https://ih0.redbubble.net/image.210602545.3386/flat,1000x1000,075,f.u1.jpg');
    $table->string('status')->default('active');
    $table->string('language')->default('English')->nullable();
    $table->boolean('verified')->default(false);
    $table->string('profile_photo')->nullable();
    $table->string('twitter_handle')->nullable();
    $table->string('facebook_handle')->nullable();
    $table->string('instagram_handle')->nullable();
    $table->string('youtube_handle')->nullable();
    $table->string('building_name')->nullable();
    $table->text('business_bio')->nullable();
    // $table->boolean('accept_mail_marketing')->default(false);
    // $table->string('zipcode')->nullable();
    // $table->timestamp('phone_verified_at')->nullable();
    // $table->boolean('tax_exempt')->default(false);
    // $table->string('company')->nullable();
    $table->string('bank_name')->nullable();
    $table->string('bank_account_number')->nullable();
    $table->string('name_on_account')->nullable();
    $table->string('sort_code')->nullable();
    $table->string('swift_code')->nullable();
    $table->string('iban')->nullable();  
    $table->foreignId('role_id')->nullable()->constrained();
    $table->rememberToken();
    $table->softDeletes();
    $table->timestamps();

   
   
    
});



## 5. Financial_detail

```php
Schema::create('financial_details', function (Blueprint $table) {
    $table->id();
     $table->foreignId('user_id')->nullable()->constrained('users');
    $table->string('bank_name')->nullable();
    $table->string('bank_account')->nullable();
    $table->string('name_on_account')->nullable();
    $table->string('sort_code')->nullable();
    $table->string('swift_code')->nullable();
    $table->string('iban')->nullable();
    $table->timestamps();
    $table->softDeletes();
   
   
    
});



## 6. Category

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug');
    $table->string('photo')->nullable(); 
    $table->timestamps();
     
    
});




## 7. Sub_Category

```php
Schema::create('sub_categories', function (Blueprint $table) {
   $table->id();
    $table->string('name');
    $table->foreignId('category_id')->constrained('categories');
    $table->string('photo')->nullable(); 
    $table->timestamps();
     
    
});



## 8. Product

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('category_id')->constrained('categories');
    $table->foreignId('sub_category_id')->nullable()->constrained('categories');
    $table->string('photo')->nullable(); with spaite media for images
    $table->string('slug');
    $table->string('sku')->nullable(); 
    $table->float('unit');
    $table->string('material')->nullable();
    $table->string('condition')->nullable();
    $table->boolean('sell_online')->default(true);
    $table->text('description')->nullable();
    $table->text('product_spec')->nullable();
    $table->string('status')->default('active');
    $table->string('ust_index')->nullable();
    $table->unsignedInteger('price')->default(0);
    $table->unsignedInteger('compare_at_price')->default(0);
    $table->unsignedInteger('cost_per_item')->default(0);
    $table->unsignedInteger('profit')->default(0);
    $table->unsignedInteger('margin')->default(0);
    $table->unsignedInteger('sales_count')->default(0);
    $table->softDeletes();
    $table->timestamps();
     
    
});


## 9. Product_attribute

```php
Schema::create('product_attributes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products');
    $table->integer('made_with_ghana_leather')->default(0);
    $table->integer('mini_stock')->default(0);
    $table->boolean('sell_out_of_stock')->default(false);
    $table->boolean('has_sku')->default(false);
    $table->string('material')->nullable();
    $table->string('condition')->nullable();
    $table->boolean('sell_online')->default(true);
    $table->text('description')->nullable();
    $table->text('product_spec')->nullable();
    $table->string('status')->default('active');
    $table->string('ust_index')->nullable();
    $table->string('storage_location')->nullable();
    $table->unsignedInteger('gross_weight')->default(0);
    $table->unsignedInteger('net_weight')->default(0);
    $table->unsignedInteger('length')->default(0);
    $table->unsignedInteger('weight')->default(0);
    $table->unsignedInteger('height')->default(0);
    $table->string('shipping_method')->nullable();   
    $table->softDeletes();
    $table->timestamps();
     
    
});




## 10. Products_variation

```php
Schema::create('products_variations', function (Blueprint $table) {
    $table->id();
    $table->string('variationName');
    $table->foreignId('product_id')->constrained('products');   
    $table->softDeletes();
    $table->timestamps();
     
    
});



## 11. Variation

```php
Schema::create('variations', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});


## 12. Variations_options
```php
Schema::create('variations_options', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('variation_id')->nullable()->constrained('variations'); 
    $table->timestamps();
});


## 13. Products_variations_option
```php
Schema::create('products_variations_options', function (Blueprint $table) {
      $table->id();
    $table->foreignId('products_variation_id')->constrained('products_variations');
    $table->string('name');
    $table->string('sku')->nullable();
    $table->unsignedInteger('price')->default(0);
    $table->timestamps();
});



## 14. Order
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products');
    $table->foreignId('user_id')->constrained('users');
    $table->string('order_number');
    $table->string('payment_status')->default('pending');
    $table->string('fulfillment_status')->default('unfulfilled'); 
    $table->unsignedInteger('sub_total')->default(0);
    $table->unsignedInteger('total_amount')->default(0);
    $table->integer('quantity')->default(0);
    $table->decimal('delivery_charge', 5, 2)->default(0);
    $table->string('order_status')->default('processing'); 
    $table->string('tracking_number')->nullable();
    $table->unsignedInteger('delivery_price')->default(0);
    $table->foreignId('delivery_id')->nullable()->constrained('delivery_companies');
    $table->softDeletes(); 
    $table->timestamp('cancelled_at')->nullable();
    $table->timestamps();
});


## 15. Delivery_company
```php
Schema::create('delivery_companies', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('account_name')->nullable();
    $table->string('account_number')->nullable();
    $table->string('bank_name')->nullable();
    $table->string('phone');
    $table->string('address');
    $table->string('logo');
    $table->boolean('verified')->default(false);
    $table->text('bio')->nullable(); 
    $table->softDeletes(); 
    $table->timestamps();
    
});






## 16. Sales
```php
Schema::create('sales', function (Blueprint $table) {
    $table->id();
     $table->foreignId('product_id')->constrained('products');
    $table->foreignId('user_id')->constrained('users');
     $table->foreignId('category_id')->constrained('categories');
    $table->softDeletes(); 
    $table->timestamps();
    
});





## 17. Review
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products');
    $table->foreignId('user_id')->constrained('users');
    $table->foreignId('category_id')->constrained('categories');
    $table->foreignId('order_id')->constrained('orders');
    $table->unsignedInteger('rating')->nullable();
    $table->string('review_status')->default('pending')->nullable();
    $table->unsignedInteger('review_count')->default(0)->nullable(); 
    $table->text('review_comment')->nullable();
    $table->softDeletes(); 
    $table->timestamps();
    
});


## 18. Post
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products');
    $table->foreignId('user_id')->constrained('users');
    $table->foreignId('category_id')->constrained('categories');
    $table->foreignId('order_id')->constrained('orders');
    $table->unsignedInteger('rating');
    $table->string('review_status')->default('pending');
    $table->text('post_comment')->nullable();
    $table->string('title');
    $table->text('description');
    $table->string('featured_img');
    $table->string('location')->nullable();
    $table->timestamp('scheduled_at')->nullable();
    $table->timestamp('published_at')->nullable();
    $table->string('slug');
    $table->unsignedBigInteger('views')->default(0); 
    $table->unsignedBigInteger('likes')->default(0);
    $table->softDeletes(); 
    $table->timestamps();
    
});


## 19. Comment
```php
 Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content',1000);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('post_id')->constrained('posts');
	    $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            $table->timestamps();
        });





## 20. Article
```php
 Schema::create('articles', function (Blueprint $table) {
            $table->id();
             $table->string('title');
             $table->string('cover_image');
            $table->text('content');
    	    $table->timestamp('published_at')->nullable();
 	 $table->foreignId('user_id')->constrained('users');
            $table->string('slug');
            $table->timestamps();
          
        });



## 21. App
```php
 Schema::create('apps', function (Blueprint $table) {
            $table->id();
             $table->string('name');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->text('description');
            $table->boolean('connect')->default(false);
           $table->string('photo')->nullable();
            $table->timestamps();
          
        });




