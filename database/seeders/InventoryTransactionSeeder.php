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
        // Define the transactions as per the specified data
        $transactions = [
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-01',
                'transaction_type' => 'Purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 2.99,
                'unit_price' => 48000,
            ],
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-05',
                'transaction_type' => 'Purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 18.05,
                'unit_price' => 48100,
            ],
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-06',
                'transaction_type' => 'Sell',
                'item_name' => 'WRD5.5',
                'quantity' => 15,
                'unit_price' => 48200,
            ],
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-08',
                'transaction_type' => 'Purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 4.05,
                'unit_price' => 47500,
            ],
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-09',
                'transaction_type' => 'Sell',
                'item_name' => 'WRD5.5',
                'quantity' => 10,
                'unit_price' => 47000,
            ],
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-15',
                'transaction_type' => 'Purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 4.06,
                'unit_price' => 48200,
            ],
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-18',
                'transaction_type' => 'Purchase',
                'item_name' => 'WRD5.5',
                'quantity' => 4.59,
                'unit_price' => 48250,
            ],
            [
                'company_name' => 'Aditya Engineering Raipur',
                'transaction_date' => '2024-09-19',
                'transaction_type' => 'Sell',
                'item_name' => 'WRD5.5',
                'quantity' => 50,
                'unit_price' => 48300,
            ],
        ];

        // Insert transactions into the database
        foreach ($transactions as $transaction) {
            InventoryTransaction::create($transaction);
        }
    }
    
}
