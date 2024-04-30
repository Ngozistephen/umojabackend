<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingMethod::create([
            'type' => 'Free Shipping', 
            'amount' => 0,
            'duration' => '7 - 30 days',
            'user_id' => 1,
        
        ]);
        ShippingMethod::create([
            'type' => 'Regular Shipping', 
            'amount' => 7.5,
            'duration' => '3 - 14 days',
            'user_id' => 1,
        
        ]);
        ShippingMethod::create([
            'type' => 'Express Shipping', 
            'amount' => 22.5,
            'duration' => '1 - 3 days',
            'user_id' => 1,
        
        ]);

      
       
      
    }
}
