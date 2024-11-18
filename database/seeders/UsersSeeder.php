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
            'name' => 'Pankaj Agrawal',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        $madan = User::create([
            'name' => 'Madan',
            'email' => 'madan@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        $aashish = User::create([
            'name' => 'Aashish',
            'email' => 'aashish@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        // Assign admin role to the user
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->assignRole($adminRole);
        
        $EmployeeRole = Role::where('name', 'Employee')->first();
        
        $madan->assignRole($EmployeeRole);
        $aashish->assignRole($EmployeeRole);
    }
}
