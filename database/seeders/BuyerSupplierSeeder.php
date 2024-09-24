<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuyerSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'company_name' => 'Aditya Engineering Raipur',
            'address' => 'Raipur, Chhattisgarh, India',
            'mobile' => '6985745896',
            'email' => 'adityaengineering@gmail.com',
            'type' => 'buyer',
        ]);
        Company::create([
            'company_name' => 'Aarti Sponge Raipur',
            'address' => 'Raipur, Chhattisgarh, India',
            'mobile' => '6985745896',
            'email' => 'aartisponge@gmail.com',
            'type' => 'buyer',
        ]);
        Company::create([
            'company_name' => 'Ali Bhai-Hakimi Industries Raipur',
            'address' => 'Raipur, Chhattisgarh, India',
            'mobile' => '6985745896',
            'email' => 'alibhai@gmail.com',
            'type' => 'buyer',

        ]);
        Company::create([
            'company_name' => 'Atul Agrawal-Shristy Power Raipur',
            'address' => 'Nagpur, Maharashtra, India',
            'mobile' => '6985745896',
            'email' => 'atulagrawal@gmail.com',
            'type' => 'buyer',

        ]);
        Company::create([
            'company_name' => 'Ganesh Agrasen-Shree  Raipur',
            'address' => 'Nagpur, Maharashtra, India',
            'mobile' => '6985745896',
            'email' => 'ganeshagrasen@gmail.com',
            'type' => 'supplier',
            'virtual_store' => 'Ganesh_VS',


        ]);
        Company::create([
            'company_name' => 'Mittal Wire Raipur',
            'address' => 'Nagpur, Maharashtra, India',
            'mobile' => '6985745896',
            'email' => 'mittalwire@gmail.com',
            'type' => 'supplier',
            'virtual_store' => 'Mittal_VS',


        ]);
        Company::create([
            'company_name' => 'Nikhil Patel-Suryanarayan Raipur',
            'address' => 'Nagpur, Maharashtra, India',
            'mobile' => '6985745896',
            'email' => 'nikhilpatel@gmail.com',
            'type' => 'supplier',
            'virtual_store' => 'Nikhil_VS',


        ]);
        Company::create([
            'company_name' => 'Saroj Singh-Maa Ambey Raipur',
            'address' => 'Nagpur, Maharashtra, India',
            'mobile' => '6985745896',
            'email' => 'sarojsingh@gmail.com',
            'type' => 'supplier',
            'virtual_store' => 'Saroj_VS',


        ]);

    }
}
