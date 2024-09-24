<?php

namespace App\Exports;

use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportSubCategory implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $sub_data = SubCategory::join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->select(
                "subcategories.id",
                "categories.name as category_name",
                "subcategories.sub_category",
                "subcategories.weight",
                "subcategories.provider",
                "subcategories.difference"
            )
            ->get();

        // Decode the provider field and get the company names
        $sub_data = $sub_data->map(function ($item) {
            $providerIds = json_decode($item->provider, true);
            $companyNames = DB::table('companies')
                ->whereIn('id', $providerIds)
                ->pluck('company_name')
                ->toArray();

                $item->provider = $companyNames;
            return $item;
        });

        return $sub_data;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["id", "Category", "Sub Category", "Weight", "Provider", "Difference"];
    }
}
