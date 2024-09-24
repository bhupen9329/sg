<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('email_settings')->insert([
            'id' => 1,
            'mailer' => 'smtp',
            'host' => 'smtp-relay.brevo.com',
            'port' => '587',
            'username' => '7713dc002@smtp-brevo.com',
            'key' => '1fhWK9GAdIrUwHq8', // Assuming `key` column in your table
            'from_address' => 'bhupendra.yasham@gmail.com',
            'from_name' => 'Saraswati Globals',
            'created_at' => '2024-02-10 06:00:16',
            'updated_at' => '2024-02-10 06:00:16',
        ]);
    }
}
