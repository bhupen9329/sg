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
        $transactions = [
            [
                'transaction_date' => '2024-09-01', // Date in the correct format
                'transaction_type' => 'purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 2.99,
                'unit_price' => 48000
            ],
            [
                'transaction_date' => '2024-09-05',
                'transaction_type' => 'purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 18.05,
                'unit_price' => 48100
            ],
            [
                'transaction_date' => '2024-09-06',
                'transaction_type' => 'sell',
                'item_name' => 'WRD5.5',
                'quantity' => 15, // Use positive values for sell transactions (handled by logic)
                'unit_price' => 47995
            ],
            [
                'transaction_date' => '2024-09-08',
                'transaction_type' => 'purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 4.05,
                'unit_price' => 47500
            ],
            [
                'transaction_date' => '2024-09-09',
                'transaction_type' => 'sell',
                'item_name' => 'WRD5.5',
                'quantity' => 10, // Use positive values for sell transactions (handled by logic)
                'unit_price' => 48000
            ],
            [
                'transaction_date' => '2024-09-15',
                'transaction_type' => 'purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 4.06,
                'unit_price' => 48200
            ],
            [
                'transaction_date' => '2024-09-18',
                'transaction_type' => 'purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 4.59,
                'unit_price' => 48250
            ],
            [
                'transaction_date' => '2024-09-19',
                'transaction_type' => 'sell',
                'item_name' => 'WRD5.5',
                'quantity' => 50,
                'unit_price' => 48000
            ],
        ];

        foreach ($transactions as $transaction) {
            InventoryTransaction::create($transaction); // Create the transaction in the database
        }
    }
}
