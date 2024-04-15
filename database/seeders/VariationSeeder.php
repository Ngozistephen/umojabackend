<?php

namespace Database\Seeders;

use App\Models\Variation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Variation::create(['name' => 'size', 'user_id' => 1]);
        Variation::create(['name' => 'color', 'user_id' => 1]);
        Variation::create(['name' => 'material','user_id' => 1]);
        Variation::create(['name' => 'style', 'user_id' => 1]);
    }
}
