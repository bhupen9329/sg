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
            'sub_category' => 'WRD 5 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 6 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 7 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 8 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 9 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 10 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 12 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 14 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '1',
            'sub_category' => 'WRD 16 MM',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '2',
            'sub_category' => 'HB 14',
            'difference' => '100',
        ]);
        SubCategory::create([
            'category_id' => '2',
            'sub_category' => 'HB 13',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '2',
            'sub_category' => 'HB 12',
            'difference' => '100',
        ]);

        SubCategory::create([
            'category_id' => '2',
            'sub_category' => 'HB 11',
            'difference' => '100',
        ]);
       
    }
}
