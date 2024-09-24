<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $stock_manager = User::create([
            'name' => 'Store Manager',
            'email' => 'sm@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        // Assign admin role to the user
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->assignRole($adminRole);
        
        $stock_managerRole = Role::where('name', 'SM')->first();
        $stock_manager->assignRole($stock_managerRole);
    }
}
