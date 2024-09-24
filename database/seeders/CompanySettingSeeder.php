<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('company_settings')->insert([
            'id' => 1,
            'name' => 'Saraswati Globals',
            'email' => 'saraswatiglobals@gmail.com',
            'phone_number' => '1234567890',
            'country' => 'India',
            'state' => 'Chhattisgarh',
            'city' => 'Raipur',
            'address' => 'NEAR KHADI BHANDAR PANDRI,RAIPUR Plot 12, Streel No. 5,492004,Raipur, Pandari, Raipur-492001, Chhattisgarh, India',
            'gst_no' => '22AFTPA7797N2ZM',
            'pan' => 'AFTPA7797N',
            'tan' => 'AFTPA7797N',
            'ac_number' => '181705001134',
            'ifsc_code' => '22AJCPJ2258A1ZZ',
            'bank_name' => 'PUNJAB NATIONAL BANK',
            'branch' => 'Raipur',
            'custom_due_date' => '10',
            'created_at' => '2024-02-10 06:00:16',
            'updated_at' => '2024-02-10 06:00:16',
        ]);
    }
}
