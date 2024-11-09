<?php

namespace Database\Seeders;

use App\Models\FreightRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreightTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FreightRate::create([
            'freight_rate_date' => '2024-11-09',
            'freight_rate' => '265',
            'insurance_rate' => '11',
            'remarks' => 'Remarks',
        ]);
    }
}
