<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GSTSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gst_settings')->insert([
            'id' => 1,
            'gst_prefix' => 'GST@18',
            'percent' => '18',
        ]);
    }
}
