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
        Permission::firstOrCreate(['name' => 'Dashboard-set-base-pirce']);
        Permission::firstOrCreate(['name' => 'Dashboard-set-notes']);

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

        // Permission for Company
        Permission::firstOrCreate(['name' => 'Company-index']);
        Permission::firstOrCreate(['name' => 'Company-create']);
        Permission::firstOrCreate(['name' => 'Company-edit']);
        Permission::firstOrCreate(['name' => 'Company-delete']);

        // Permission for Warehouse
        Permission::firstOrCreate(['name' => 'Warehouse-index']);
        Permission::firstOrCreate(['name' => 'Warehouse-create']);
        Permission::firstOrCreate(['name' => 'Warehouse-edit']);
        Permission::firstOrCreate(['name' => 'Warehouse-delete']);


        // Permission for Setting
        Permission::firstOrCreate(['name' => 'Setting-company']);
        Permission::firstOrCreate(['name' => 'Setting-email']);

        // Permission for Sub GST
        Permission::firstOrCreate(['name' => 'GST-index']);
        Permission::firstOrCreate(['name' => 'GST-create']);
        Permission::firstOrCreate(['name' => 'GST-edit']);
        Permission::firstOrCreate(['name' => 'GST-delete']);

        // Permission for Quotation
        Permission::firstOrCreate(['name' => 'Quotation-index']);
        Permission::firstOrCreate(['name' => 'Quotation-view']);
        Permission::firstOrCreate(['name' => 'Quotation-create']);
        Permission::firstOrCreate(['name' => 'Quotation-edit']);
        Permission::firstOrCreate(['name' => 'Quotation-delete']);
        Permission::firstOrCreate(['name' => 'Quotation-email']);
        Permission::firstOrCreate(['name' => 'Quotation-download']);


        // Permission for Category
        Permission::firstOrCreate(['name' => 'Category-index']);
        Permission::firstOrCreate(['name' => 'Category-view']);
        Permission::firstOrCreate(['name' => 'Category-create']);
        Permission::firstOrCreate(['name' => 'Category-edit']);
        Permission::firstOrCreate(['name' => 'Category-delete']);

        // Permission for Sub Category
        Permission::firstOrCreate(['name' => 'Sub-Category-index']);
        Permission::firstOrCreate(['name' => 'Sub-Category-view']);
        Permission::firstOrCreate(['name' => 'Sub-Category-create']);
        Permission::firstOrCreate(['name' => 'Sub-Category-edit']);
        Permission::firstOrCreate(['name' => 'Sub-Category-delete']);


        // Permission for Purchase
        Permission::firstOrCreate(['name' => 'Purchase-index']);
        Permission::firstOrCreate(['name' => 'Purchase-view']);
        Permission::firstOrCreate(['name' => 'Purchase-create']);
        Permission::firstOrCreate(['name' => 'Purchase-edit']);
        Permission::firstOrCreate(['name' => 'Purchase-close']);
        Permission::firstOrCreate(['name' => 'Purchase-delete']);


        // Permission for Inward
        Permission::firstOrCreate(['name' => 'Inward-index']);
        Permission::firstOrCreate(['name' => 'Inward-Credit-Note']);
        Permission::firstOrCreate(['name' => 'Inward-view']);
        Permission::firstOrCreate(['name' => 'Inward-create']);
        Permission::firstOrCreate(['name' => 'Inward-edit']);
        Permission::firstOrCreate(['name' => 'Inward-approve']);
        Permission::firstOrCreate(['name' => 'Inward-delete']);


        // Permission for Stock
        Permission::firstOrCreate(['name' => 'Stock-index']);
        Permission::firstOrCreate(['name' => 'Stock-create']);



        // Permission for Outward
        Permission::firstOrCreate(['name' => 'Outward-index']);
        Permission::firstOrCreate(['name' => 'Outward-view']);
        Permission::firstOrCreate(['name' => 'Outward-create']);
        Permission::firstOrCreate(['name' => 'Outward-edit']);
        Permission::firstOrCreate(['name' => 'Outward-approve']);
        Permission::firstOrCreate(['name' => 'Outward-delete']);




        // Permission for Stock Adjustment
        Permission::firstOrCreate(['name' => 'Stock-Adjustment-index']);
        Permission::firstOrCreate(['name' => 'Stock-Adjustment-delete']);
        Permission::firstOrCreate(['name' => 'Stock-Adjustment-create']);



        // Permission for sales
        Permission::firstOrCreate(['name' => 'Sales-index']);
        Permission::firstOrCreate(['name' => 'Sales-view']);
        Permission::firstOrCreate(['name' => 'Sales-create']);
        Permission::firstOrCreate(['name' => 'Sales-close']);
        Permission::firstOrCreate(['name' => 'Sales-edit']);
        Permission::firstOrCreate(['name' => 'Sales-delete']);

        // Permission for Reports
        Permission::firstOrCreate(['name' => 'PO-Report']);
        Permission::firstOrCreate(['name' => 'SO-Report']);
        Permission::firstOrCreate(['name' => 'Quotation-Report']);
        Permission::firstOrCreate(['name' => 'Inward-Report']);
        Permission::firstOrCreate(['name' => 'Outward-Report']);
        Permission::firstOrCreate(['name' => 'Stock-Report']);
        Permission::firstOrCreate(['name' => 'Top-Selling-Report']);
        Permission::firstOrCreate(['name' => 'Stock-Transaction-Report']);
        Permission::firstOrCreate(['name' => 'Ageing-Report']);

        //Price permission 
        Permission::firstOrCreate(['name' => 'price']);

        //Stock Transaction permission 
        Permission::firstOrCreate(['name' => 'stock-transaction']);


        $admin = Role::create(['name' => 'Admin']);
        $stock_manager = Role::create(['name' => 'SM']);

        $admin->givePermissionTo([

            'Dashboard-set-base-pirce',
            'Dashboard-set-notes',

            'Role-index',
            'Role-create',
            'Role-edit',
            'Role-delete',

            'User-index',
            'User-create',
            'User-edit',
            'User-delete',



            'Company-index',
            'Company-create',
            'Company-edit',
            'Company-delete',

            'Warehouse-index',
            'Warehouse-create',
            'Warehouse-edit',
            'Warehouse-delete',

            'Setting-company',
            'Setting-email',


            'GST-index',
            'GST-create',
            'GST-edit',
            'GST-delete',

            'Quotation-index',
            'Quotation-view',
            'Quotation-create',
            'Quotation-edit',
            'Quotation-delete',
            'Quotation-email',
            'Quotation-download',

            'Sub-Category-index',
            'Sub-Category-view',
            'Sub-Category-create',
            'Sub-Category-edit',
            'Sub-Category-delete',

            'Category-index',
            'Category-view',
            'Category-create',
            'Category-edit',
            'Category-delete',

            'Purchase-index',
            'Purchase-view',
            'Purchase-create',
            'Purchase-edit',
            'Purchase-close',
            'Purchase-delete',



            'Sales-index',
            'Sales-view',
            'Sales-create',
            'Sales-edit',
            'Sales-close',
            'Sales-delete',


            'Inward-index',
            'Inward-view',
            'Inward-create',
            'Inward-edit',
            'Inward-approve',
            'Inward-delete',
            'Inward-Credit-Note',


            'Outward-index',
            'Outward-view',
            'Outward-create',
            'Outward-edit',
            'Outward-approve',
            'Outward-delete',


            'Stock-index',
            'Stock-create',



            'PO-Report',
            'SO-Report',
            'Quotation-Report',
            'Inward-Report',
            'Outward-Report',
            'Stock-Report',
            'Top-Selling-Report',
            'Stock-Transaction-Report',
            // 'Quotation-Execution-Report',
            'Ageing-Report',

            'Stock-Adjustment-index',
            'Stock-Adjustment-delete',
            'Stock-Adjustment-create',

            'price',

            'stock-transaction',

        ]);

        $stock_manager->givePermissionTo([

            'Inward-index',
            'Inward-view',
            'Inward-create',
            'Inward-edit',
            'Inward-approve',
            'Inward-delete',
            'Inward-Credit-Note',
        ]);
    }
}
