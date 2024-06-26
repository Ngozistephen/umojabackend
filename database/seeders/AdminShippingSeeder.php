<?php

namespace Database\Seeders;

use App\Models\AdminShipping;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminShipping::create(['name' => 'manual shipping', 'user_id' => 1]);
        AdminShipping::create(['name' => 'umoja logistics', 'user_id' => 1]);
    }
}
