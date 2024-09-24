<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubCategory::create([
            'category_id' => '1',
            'sub_category' => '5.5',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '2',
            'sub_category' => '8',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '3',
            'sub_category' => '18-R',
            'difference' => '100',
        ]);
       
    }
}
