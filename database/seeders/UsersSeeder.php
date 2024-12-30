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
    public function run(): void
    {
        // Create users
        $admin = User::create([
            'name' => 'Pankaj Agrawal',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678') // Ideally, fetch password from env or .env file
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
    
        $deshmukh = User::create([
            'name' => 'Deshmukh',
            'email' => 'deshmukh@gmail.com',
            'password' => Hash::make('12345678')
        ]);
    
        // Ensure roles exist or create them if they don't
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $accountantRole = Role::firstOrCreate(['name' => 'Accountant']);
        $salespersonRole = Role::firstOrCreate(['name' => 'Sales Person']);
        $dispatchRole = Role::firstOrCreate(['name' => 'Dispatch']);
    
        // Assign roles to users
        $admin->assignRole($adminRole);
        $deshmukh->assignRole($accountantRole);
        $madan->assignRole($salespersonRole);
        $aashish->assignRole($dispatchRole);
    }
    
}
