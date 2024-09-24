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
                'transaction_date' => Carbon::create(2023, 3, 1),
                'transaction_type' => 'purchase',
                'quantity' => 600,
                'unit_price' => 5.00,
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 5),
                'transaction_type' => 'purchase',
                'quantity' => 200,
                'unit_price' => 5.10,
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 9),
                'transaction_type' => 'issue',
                'quantity' => -500,
                'unit_price' => null, // Not required for issues
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 11),
                'transaction_type' => 'purchase',
                'quantity' => 100,
                'unit_price' => 4.50,
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 16),
                'transaction_type' => 'purchase',
                'quantity' => 300,
                'unit_price' => 5.50,
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 20),
                'transaction_type' => 'issue',
                'quantity' => -250,
                'unit_price' => null, // Not required for issues
            ],
            [
                'transaction_date' => Carbon::create(2023, 3, 29),
                'transaction_type' => 'issue',
                'quantity' => -300,
                'unit_price' => null, // Not required for issues
            ],
        ];

        foreach ($transactions as $transaction) {
            InventoryTransaction::create($transaction);
        }
    }
}
