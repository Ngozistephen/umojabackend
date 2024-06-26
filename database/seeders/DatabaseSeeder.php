<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\AdminShippingSeeder;
use Database\Seeders\ShippingMethodSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call(PermissionSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(VariationsOptionSeeder::class);
        $this->call(VariationSeeder::class);
        $this->call(ShippingMethodSeeder::class);
        $this->call(BusinessTypeSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(AdminShippingSeeder::class);
    }
}
