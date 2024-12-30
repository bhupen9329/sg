<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dashboard Permission for roles
        Permission::firstOrCreate(['name' => 'Dashboard']);

        // Permission for roles Create
        Permission::firstOrCreate(['name' => 'Role-index']);
        Permission::firstOrCreate(['name' => 'Role-create']);
        Permission::firstOrCreate(['name' => 'Role-edit']);
        Permission::firstOrCreate(['name' => 'Role-delete']);

        // Permission for User Create
        Permission::firstOrCreate(['name' => 'User-index']);
        Permission::firstOrCreate(['name' => 'User-create']);
        Permission::firstOrCreate(['name' => 'User-edit']);
        Permission::firstOrCreate(['name' => 'User-delete']);


        // Permission for Base Item
        Permission::firstOrCreate(['name' => 'Base Item-index']);
        Permission::firstOrCreate(['name' => 'Base Item-create']);
        Permission::firstOrCreate(['name' => 'Base Item-edit']);
        Permission::firstOrCreate(['name' => 'Base Item-delete']);

        // Permission for Conversion Item
        Permission::firstOrCreate(['name' => 'Conversion-index']);
        Permission::firstOrCreate(['name' => 'Conversion-create']);
        Permission::firstOrCreate(['name' => 'Conversion-edit']);
        Permission::firstOrCreate(['name' => 'Conversion-delete']);

        // Permission for Conversion Item Rate
        Permission::firstOrCreate(['name' => 'Conversion Rate-index']);
        Permission::firstOrCreate(['name' => 'Conversion Rate-create']);
        Permission::firstOrCreate(['name' => 'Conversion Rate-edit']);
        Permission::firstOrCreate(['name' => 'Conversion Rate-delete']);


        // Permission for Conversion Stock
        Permission::firstOrCreate(['name' => 'Stocks-index']);
        Permission::firstOrCreate(['name' => 'Stocks-create']);
        Permission::firstOrCreate(['name' => 'Stocks-edit']);
        Permission::firstOrCreate(['name' => 'Stocks-delete']);



        // Permission for Setting Compnay
        Permission::firstOrCreate(['name' => 'Setting-company']);

        // Additional Report
        Permission::firstOrCreate(['name' => 'Additional-Reports']);

        // Reports
        Permission::firstOrCreate(['name' => 'Reports']);


        // Permission for Dispatch
        Permission::firstOrCreate(['name' => 'Dispatch-index']);
        Permission::firstOrCreate(['name' => 'Dispatch-create']);
        Permission::firstOrCreate(['name' => 'Dispatch-edit']);
        Permission::firstOrCreate(['name' => 'Dispatch-delete']);

        // Permission for Buyers & Suppliers
        Permission::firstOrCreate(['name' => 'Buyers & Suppliers-index']);
        Permission::firstOrCreate(['name' => 'Buyers & Suppliers-create']);
        Permission::firstOrCreate(['name' => 'Buyers & Suppliers-edit']);
        Permission::firstOrCreate(['name' => 'Buyers & Suppliers-delete']);


        // Permission for Sales
        Permission::firstOrCreate(['name' => 'Sales-index']);
        Permission::firstOrCreate(['name' => 'Sales-create']);
        Permission::firstOrCreate(['name' => 'Sales-edit']);
        Permission::firstOrCreate(['name' => 'Sales-delete']);


        // Permission for Sales
        Permission::firstOrCreate(['name' => 'Purchase-index']);
        Permission::firstOrCreate(['name' => 'Purchase-create']);
        Permission::firstOrCreate(['name' => 'Purchase-edit']);
        Permission::firstOrCreate(['name' => 'Purchase-delete']);



        $admin = Role::create(['name' => 'Admin']);
        $accountant = Role::create(['name' => 'Accountant']);
        $dispatch = Role::create(['name' => 'Dispatch']);
        $salesperson = Role::create(['name' => 'Sales Person']);

        $admin->givePermissionTo([

            'Dashboard',
            'Role-index',
            'Role-create',
            'Role-edit',
            'Role-delete',

            'User-index',
            'User-create',
            'User-edit',
            'User-delete',

            'Base Item-index',
            'Base Item-create',
            'Base Item-edit',
            'Base Item-delete',

            'Conversion-index',
            'Conversion-create',
            'Conversion-edit',
            'Conversion-delete',

            'Conversion Rate-index',
            'Conversion Rate-create',
            'Conversion Rate-edit',
            'Conversion Rate-delete',

            'Stocks-index',
            'Stocks-create',
            'Stocks-edit',
            'Stocks-delete',

            'Setting-company',

            'Additional-Reports',

            'Reports',

            'Dispatch-index',
            'Dispatch-create',
            'Dispatch-edit',
            'Dispatch-delete',

            'Buyers & Suppliers-index',
            'Buyers & Suppliers-create',
            'Buyers & Suppliers-edit',
            'Buyers & Suppliers-delete',

            'Sales-index',
            'Sales-create',
            'Sales-edit',
            'Sales-delete',
            
            'Purchase-index',
            'Purchase-create',
            'Purchase-edit',
            'Purchase-delete'

        ]);


        $accountant->givePermissionTo([

           
            'Base Item-index',
            'Base Item-create',
            'Base Item-edit',
            'Base Item-delete',

            'Conversion-index',
            'Conversion-create',
            'Conversion-edit',
            'Conversion-delete',

            'Conversion Rate-index',
            'Conversion Rate-create',
            'Conversion Rate-edit',
            'Conversion Rate-delete',

            'Stocks-index',
            'Stocks-create',
            'Stocks-edit',
            'Stocks-delete',

    
            'Additional-Reports',
            'Reports',

            'Dispatch-index',
            'Dispatch-create',
            'Dispatch-edit',
            'Dispatch-delete',

            'Buyers & Suppliers-index',
            'Buyers & Suppliers-create',
            'Buyers & Suppliers-edit',
            'Buyers & Suppliers-delete',

            'Sales-index',
            'Sales-create',
            'Sales-edit',
            'Sales-delete',

            'Purchase-index',
            'Purchase-create',
            'Purchase-edit',
            'Purchase-delete'

        ]);

        $dispatch->givePermissionTo([

        
            'Base Item-index',
            'Conversion-index',
            'Conversion Rate-index',
            'Stocks-index',

          
            'Dispatch-index',
            'Buyers & Suppliers-index',
            'Sales-index',
            'Purchase-index',


        ]);


        $salesperson->givePermissionTo([

          
        
            'Stocks-index',
      
            'Dispatch-index',
            'Buyers & Suppliers-index',
            'Sales-index',
            'Purchase-index',

        ]);
    }
}
