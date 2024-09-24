<?php

namespace App\Imports;

use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class SubCategoryImport implements ToCollection
class SubCategoryImport implements ToModel, WithHeadingRow    
{
    //  /**
    // * @param array $row
    // *
    // * @return \Illuminate\Database\Eloquent\Model|null
    // */
    // public function model(array $row)
    // {
    //     return new SubCategory([
    //         // 'id'     => $row['id'],
    //         'category_id'     => $row['category_id'],
    //         'sub_category'    => $row['sub_category'], 
    //         'weight'    => $row['weight'], 
    //         'provider'    => $row['provider'], 
    //         'difference'    => $row['difference'], 
    //     ]);


    // }


      /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $subcategory = SubCategory::where('id', $row['id'])
            ->first();

        if ($subcategory) {
            // Update the record with the new data
            $subcategory->update([
                'difference' => $row['difference'] ?? 0,
            ]);
        }

        // Return null to not create new records
        return null;
    }
   
}
