<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\InventoryTransaction;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InventoryTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the transactions
        $transactions = [
            [
                'transaction_date' => Carbon::create(2023, 3, 1),
                'transaction_type' => 'purchase',
                'quantity' => 600,
                'unit_price' => 5.00,
                'item_name' => 'WRD',
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 5),
                'transaction_type' => 'purchase',
                'quantity' => 200,
                'unit_price' => 5.10,
                'item_name' => 'WRD',
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 9),
                'transaction_type' => 'sell', // Changed from 'issue' to 'sell'
                'quantity' => -500,
                'unit_price' => 6.00, // Assuming a selling price; required for profit calculation
                'item_name' => 'WRD',
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 11),
                'transaction_type' => 'purchase',
                'quantity' => 100,
                'unit_price' => 4.50,
                'item_name' => 'WRD',
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 16),
                'transaction_type' => 'purchase',
                'quantity' => 300,
                'unit_price' => 5.50,
                'item_name' => 'WRD',
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 20),
                'transaction_type' => 'sell', // Changed from 'issue' to 'sell'
                'quantity' => -250,
                'unit_price' => 6.50, // Assuming a selling price; required for profit calculation
                'item_name' => 'WRD',
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 29),
                'transaction_type' => 'sell', // Changed from 'issue' to 'sell'
                'quantity' => -300,
                'unit_price' => 7.00, 
                'item_name' => 'WRD',
                // Assuming a selling price; required for profit calculation
            ],
        ];

        // Insert transactions into the database
        foreach ($transactions as $transaction) {
            InventoryTransaction::create($transaction);
        }
    }
    
}
