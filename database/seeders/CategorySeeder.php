<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'WRD',
            'price' => 48000,
            'margin' => 2500,
        ]);

        Category::create([
            'name' => 'HB',
            'price' => 40500,
            'margin' => 2500,
        ]);

        Category::create([
            'name' => 'MS',
            'price' => 68200,
            'margin' => 315,
        ]);

         

    }
}
