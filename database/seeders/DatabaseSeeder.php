<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UsersSeeder::class,
            CityandStateSeeder::class,
            CompanySettingSeeder::class,
            EmailSettingSeeder::class,
            GstSettingSeeder::class,
            CategorySeeder::class,
            BuyerSupplierSeeder::class,
            WareHouseSeeder::class,
            SubCategorySeeder::class,
            InventoryTransactionSeeder::class,
   
        ]);
    }
}
