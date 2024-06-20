<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessType::create(['name' => 'fashion', 'user_id' => 1]);
        BusinessType::create(['name' => 'cosmetic', 'user_id' => 1]);
        BusinessType::create(['name' => 'art', 'user_id' => 1]);
        BusinessType::create(['name' => 'home decoration', 'user_id' => 1]);
        BusinessType::create(['name' => 'furniture', 'user_id' => 1]);
        BusinessType::create(['name' => 'accessories', 'user_id' => 1]);
    }
}
