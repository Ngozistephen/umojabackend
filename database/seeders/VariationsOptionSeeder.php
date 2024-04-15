<?php

namespace Database\Seeders;

use App\Models\VariationOption;
use Illuminate\Database\Seeder;
use App\Models\VariationsOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VariationsOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VariationsOption::create([
            'name' => 'A5',
            'variation_id' => 2,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'B5',
            'variation_id' => 2,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Black',
            'variation_id' => 3,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Brown',
            'variation_id' => 3,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Blue',
            'variation_id' => 3,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Yellow',
            'variation_id' => 3,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Red',
            'variation_id' => 3,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Grid',
            'variation_id' => 4,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Single lined',
            'variation_id' => 4,
            'user_id' => 1
        ]);
        VariationsOption::create([
            'name' => 'Blank',
            'variation_id' => 4,
            'user_id' => 1
        ]);
    }
}
