<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryTransaction;
use Carbon\Carbon;


class ValuationController extends Controller
{
    public function index()
    {
        $inventory = InventoryTransaction::all();
        // dd($inventory);
        return view('inventory_valuation.index',compact('inventory'));
    }

    // public function calculateLIFO()
    // {
    //     $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
    //     $inventoryStack = [];  
    //     $transactionLogs = []; 
    //     $totalQuantity = 0;
    //     $totalValue = 0;
    //     $totalProfitLoss = 0;
    
    //     foreach ($transactions as $transaction) {
    //         if (strtolower($transaction->transaction_type) === 'purchase') {
                
    //             $inventoryStack[] = [
    //                 'quantity' => $transaction->quantity,
    //                 'unit_price' => $transaction->unit_price,
    //                 'transaction_date' => $transaction->transaction_date,
    //             ];
    
                
    //             $totalQuantity += $transaction->quantity;
    //             $totalValue += $transaction->quantity * $transaction->unit_price;
    
               
    //             $transactionLogs[] = [
    //                 'transaction_type' => 'Purchase',
    //                 'quantity' => $transaction->quantity,
    //                 'unit_price' => $transaction->unit_price,
    //                 'transaction_date' => $transaction->transaction_date,
    //                 'balance_qty' => $totalQuantity,
    //                 'balance_value' => $totalValue, 
    //                 'cost_of_goods_sold' => 0,
    //                 'profit_loss' => 0,
    //                 'status' => 'Long', 
    //             ];
    
    //         } elseif (strtolower($transaction->transaction_type) === 'sell') {
    //             $sellQty = abs($transaction->quantity); 
    //             $costOfGoodsSold = 0; 
    //             $logEntry = [
    //                 'transaction_type' => 'Sell',
    //                 'quantity' => $sellQty,
    //                 'transaction_date' => $transaction->transaction_date,
    //                 'selling_price' => $transaction->unit_price,
    //                 'details' => [],
    //             ];
    
               
    //             while ($sellQty > 0 && !empty($inventoryStack)) {
    //                 $lastPurchase = array_pop($inventoryStack); 
    
    //                 if ($lastPurchase['quantity'] >= $sellQty) {
                        
    //                     $costOfGoodsSold += $sellQty * $lastPurchase['unit_price'];
    //                     $totalQuantity -= $sellQty;
    //                     $totalValue -= $sellQty * $lastPurchase['unit_price'];
    
                     
    //                     $remainingQty = $lastPurchase['quantity'] - $sellQty;
    //                     $remainingValue = $remainingQty * $lastPurchase['unit_price'];
    
                      
    //                     if ($remainingQty > 0) {
    //                         $inventoryStack[] = [
    //                             'quantity' => $remainingQty,
    //                             'unit_price' => $lastPurchase['unit_price'],
    //                             'transaction_date' => $lastPurchase['transaction_date'],
    //                         ];
    //                     }
    
    //                     $logEntry['details'][] = [
    //                         'used_qty' => $sellQty,
    //                         'unit_price' => $lastPurchase['unit_price'],
    //                         'remaining_qty' => $remainingQty,
    //                         'remaining_value' => $remainingValue,
    //                     ];
    //                     $sellQty = 0; 
    
    //                 } else {
                       
    //                     $costOfGoodsSold += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
    //                     $sellQty -= $lastPurchase['quantity'];
    //                     $totalQuantity -= $lastPurchase['quantity'];
    //                     $totalValue -= $lastPurchase['quantity'] * $lastPurchase['unit_price'];
    
                       
    //                     $logEntry['details'][] = [
    //                         'used_qty' => $lastPurchase['quantity'],
    //                         'unit_price' => $lastPurchase['unit_price'],
    //                         'remaining_qty' => 0,
    //                         'remaining_value' => 0,
    //                     ];
    //                 }
    //             }
    
                
    //             $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
    //             $profitLoss = $totalSaleValue - $costOfGoodsSold;
    //             $totalProfitLoss += $profitLoss;
    
            
    //             $breakEvenPrice = $costOfGoodsSold / abs($transaction->quantity);
    
    //             $transactionLogs[] = [
    //                 'transaction_type' => 'Sell',
    //                 'quantity' => abs($transaction->quantity),
    //                 'selling_price' => $transaction->unit_price,
    //                 'transaction_date' => $transaction->transaction_date,
    //                 'balance_qty' => $totalQuantity,
    //                 'balance_value' => $totalValue, 
    //                 'cost_of_goods_sold' => $costOfGoodsSold,
    //                 'profit_loss' => $profitLoss,
    //                 'total_profit_loss' => $totalProfitLoss,
    //                 'status' => $totalQuantity >= 0 ? 'Long' : 'Short', 
    //                 'details' => $logEntry['details'], 
    //                 'break_even_price' => $breakEvenPrice, 
    //             ];
    //             // dd($transactionLogs);
    //         }
    //     }
    
    //     return view('inventory_valuation.lifo', [
    //         'transaction_logs' => $transactionLogs,
    //         'final_balance_qty' => $totalQuantity,
    //         'final_balance_value' => $totalValue, 
    //         'final_profit_loss' => $totalProfitLoss,
    //     ]);
    // }
    

    public function calculateLIFO()
    {
        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get(); 
        $inventoryStack = [];  
        $transactionLogs = []; 
        $totalQuantity = 0;
        $totalValue = 0;
        $totalProfitLoss = 0;
    
        foreach ($transactions as $transaction) {
            if (strtolower($transaction->transaction_type) === 'purchase') {
                // Add the purchase to the inventory stack (LIFO)
                $inventoryStack[] = [
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                ];
    
                // Update total quantity and value for stock
                $totalQuantity += $transaction->quantity;
                $totalValue += $transaction->quantity * $transaction->unit_price;
    
                // Log purchase details
                $transactionLogs[] = [
                    'transaction_type' => 'Purchase',
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue, 
                    'cost_of_goods_sold' => 0,
                    'profit_loss' => 0,
                    'status' => 'Long', 
                    'details' => [
                        [
                            'used_qty' => $transaction->quantity,
                            'unit_price' => $transaction->unit_price,
                            'remaining_qty' => 0, // All of this purchase is accounted for
                            'remaining_value' => 0, // No remaining value
                        ],
                    ], // Include purchase details in the log
                ];
                $lastTransactionStatus = 'Long';
    
            } elseif (strtolower($transaction->transaction_type) === 'sell') {
                $sellQty = abs($transaction->quantity); // Quantity to sell
                $costOfGoodsSold = 0; 
                $logEntry = [
                    'transaction_type' => 'Sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $transaction->unit_price, // Add selling price here
                    'details' => [],
                ];
    
                while ($sellQty > 0 && !empty($inventoryStack)) {
                    $lastPurchase = array_pop($inventoryStack);
                    
                    if ($lastPurchase['quantity'] >= $sellQty) {
                        // Case where purchase quantity is enough to cover the sell quantity
                        $costOfGoodsSold += $sellQty * $lastPurchase['unit_price'];
                        $totalQuantity -= $sellQty;
                        $totalValue -= $sellQty * $lastPurchase['unit_price'];
    
                        // Calculate remaining quantity
                        $remainingQty = $lastPurchase['quantity'] - $sellQty;
    
                        // If there's any remaining quantity in this batch, push it back into the stack
                        if ($remainingQty > 0) {
                            $inventoryStack[] = [
                                'quantity' => $remainingQty,
                                'unit_price' => $lastPurchase['unit_price'],
                                'transaction_date' => $lastPurchase['transaction_date'],
                            ];
                        }
    
                        // Log details
                        $logEntry['details'][] = [
                            'used_qty' => $sellQty,
                            'unit_price' => $lastPurchase['unit_price'],
                            'remaining_qty' => $remainingQty,  // This will be 0 if fully consumed
                            'remaining_value' => $remainingQty * $lastPurchase['unit_price'],
                        ];
                        $sellQty = 0;  // All quantity is sold
    
                    } else {
                        // Case where purchase quantity is less than the sell quantity
                        $costOfGoodsSold += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                        $sellQty -= $lastPurchase['quantity'];  // Reduce the sell quantity by what's available
                        $totalQuantity -= $lastPurchase['quantity'];
                        $totalValue -= $lastPurchase['quantity'] * $lastPurchase['unit_price'];
    
                        // Log the full use of this batch (remaining quantity will be zero)
                        $logEntry['details'][] = [
                            'used_qty' => $lastPurchase['quantity'],
                            'unit_price' => $lastPurchase['unit_price'],
                            'remaining_qty' => 0,
                            'remaining_value' => 0,
                        ];
                    }
                }
    
                // If there's still a deficit (sold more than available inventory), track the negative balance
                if ($sellQty > 0) {
                    $logEntry['details'][] = [
                        'used_qty' => 0,
                        'unit_price' => 0,  // No inventory available to match the sale
                        'remaining_qty' => -$sellQty,  // Negative balance indicating over-sell
                        'remaining_value' => 0,  // No value for the negative quantity
                    ];
                    $totalQuantity -= $sellQty;  // Update total quantity to reflect the deficit
                }
    
                // Calculate profit/loss for this transaction
                $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
                $profitLoss = $totalSaleValue - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;
    
                // Log the sell details
                $transactionLogs[] = [
                    'transaction_type' => 'Sell',
                    'quantity' => abs($transaction->quantity),
                    'selling_price' => $transaction->unit_price, // Include selling price in logs
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue, 
                    'cost_of_goods_sold' => $costOfGoodsSold,
                    'profit_loss' => $profitLoss,
                    'total_profit_loss' => $totalProfitLoss,
                    'status' => $totalQuantity >= 0 ? 'Long' : 'Short', 
                    'details' => $logEntry['details'], 
                ];
    
                $lastTransactionStatus = $transactionLogs[count($transactionLogs) - 1]['status'];
            }
        }
        
        return view('inventory_valuation.lifo', [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue, 
            'final_profit_loss' => $totalProfitLoss,
            'last_transaction_status' => $lastTransactionStatus,
        ]);
    }
    
    
    


    

    public function store_inventory(Request $request)
    {
       

      
        $transaction = new InventoryTransaction();
        $transaction->transaction_type = $request->input('type'); // Either Purchase or Sell
        $transaction->item_name = $request->input('item_name');
        $transaction->quantity = $request->input('quantity');
        $transaction->unit_price = $request->input('price');
        $transaction->transaction_date = Carbon::now();

        $transaction->save();

    
        return redirect()->back()->with('success', 'Transaction saved successfully!');
    }
    
}
