<?php

namespace Database\Seeders;

use App\Models\WareHouseModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WareHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WareHouseModel::create([
            'warehouse_title' => 'LOHA BAZAR',
            'mobile' => '9658741236',
            'address' => 'New Raipur City',
            'state' => 'Chhattisgarh',
            'city' => 'Raipur',
            'country' => 'India',
            'pincode' => '492015',
            'gstn' => '27AAACT3904F1ZM',
            'pan' => 'ERTYU3452E',
            'tan' => 'ERTYU3452E',
            'cin_no' => 'P52415CG2024PPT052369',
            'registration_no' => 'P52415CG2024PPT052369',
            'store_manager_id' => '1',
        ]);
        WareHouseModel::create([
            'warehouse_title' => 'Raipur WareHouse 2',
            'mobile' => '9658741236',
            'address' => 'New Raipur City',
            'state' => 'Chhattisgarh',
            'city' => 'Raipur',
            'country' => 'India',
            'pincode' => '492015',
            'gstn' => '27AAACT3904F1ZM',
            'pan' => 'ERTYU3452E',
            'tan' => 'ERTYU3452E',
            'cin_no' => 'P52415CG2024PPT052369',
            'registration_no' => 'P52415CG2024PPT052369',
            'store_manager_id' => '1',
        ]);
        WareHouseModel::create([
            'warehouse_title' => 'Raipur WareHouse 3',
            'mobile' => '9658741236',
            'address' => 'New Raipur City',
            'state' => 'Chhattisgarh',
            'city' => 'Raipur',
            'country' => 'India',
            'pincode' => '492015',
            'gstn' => '27AAACT3904F1ZM',
            'pan' => 'ERTYU3452E',
            'tan' => 'ERTYU3452E',
            'cin_no' => 'P52415CG2024PPT052369',
            'registration_no' => 'P52415CG2024PPT052369',
            'store_manager_id' => '1',
        ]);

   
    }
}
