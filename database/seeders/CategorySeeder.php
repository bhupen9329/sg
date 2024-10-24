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
            'name' => 'WRD 5.5',
            'price' => 48000,
            'margin' => 2500,
        ]);

        Category::create([
            'name' => 'HB 12',
            'price' => 40500,
            'margin' => 2500,
        ]);

        Category::create([
            'name' => 'GI 6-HOT DIP',
            'price' => 68200,
            'margin' => 315,
        ]); 

        Category::create([
            'name' => 'GI  14 &  16-ELECTRO',
            'price' => 40500,
            'margin' => 2500,
        ]);

        Category::create([
            'name' => 'GI 18',
            'price' => 40500,
            'margin' => 2500,
        ]);
        Category::create([
            'name' => 'MS 16',
            'price' => 40500,
            'margin' => 2500,
        ]);

        Category::create([
            'name' => 'MS 18',
            'price' => 40500,
            'margin' => 2500,
        ]);

        Category::create([
            'name' => 'MS 20',
            'price' => 40500,
            'margin' => 2500,
        ]);
         

    }
}
