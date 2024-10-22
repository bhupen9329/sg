<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryTransaction;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;


class ValuationController extends Controller
{
    public function index()
    {
        $inventory = InventoryTransaction::all();
        $companies = Company::all(); // Retrieve all companies
    
        // dd($companies);
        return view('inventory_valuation.index',compact('inventory','companies'));
    }

   
    public function calculateLIFO()
    {
        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
        $lastTransaction = $transactions->last();
        $lastTransactionDate = $lastTransaction ? $lastTransaction->transaction_date : null;
    
        $inventoryStack = []; // Holds the purchase transactions for LIFO processing
        $transactionLogs = []; // Logs all transaction details for reporting
        $totalQuantity = 0; // Total quantity in inventory
        $totalValue = 0; // Total value of inventory (based on quantity and unit price)
        $totalProfitLoss = 0; // Running total of profit/loss from sales
       // Status of the inventory (Short/Long)
        $lastPurchasePrice = null; // Last purchase price
        $lastSellPrice = null; // Last sell price
        $costOfGoodsPurchased = 0;

        $lastTransactionStatus = '';
    
        foreach ($transactions as $transaction) {

            if (strtolower($transaction->transaction_type) === 'purchase') {
                // Add purchase to the stack
      

                $poQtyCheck = abs($transaction->quantity); // Get absolute quantity for selling
                $poQty = $poQtyCheck;
                $lastPurchasePrice = $transaction->unit_price;
                $logEntry = [
                    'transaction_type' => 'Purchase',
                    'quantity' => $poQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $transaction->unit_price,
                    'details' => [],
                    'po_qty' => $poQty,
                ];
        
                if($lastTransactionStatus == 'Long' ||  $lastTransactionStatus == '' ){
                  
                    $inventoryStack[] = [
                        'quantity' => $transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                // Update inventory totals
                $totalQuantity += $transaction->quantity;
                $totalValue += $transaction->quantity * $transaction->unit_price;
                }
                else{
                
                    // dump($poQty);

                    while ($poQty > 0 && !empty($inventoryStack)) {
                        // Get the last purchase (LIFO: Last In First Out)
                        $lastPurchase = array_pop($inventoryStack);
                        
                        if ($lastPurchase['quantity'] <= $poQty) {
                            // Sufficient quantity in the last purchase to fulfill the PO
                            $costOfGoodsPurchased += $poQty * $lastPurchase['unit_price'];
                            $remainingQty = $lastPurchase['quantity'] + $poQty;
                         
                            // Update total quantity and value for purchases
                            $totalQuantity +=  $poQty;  // Assuming adding for purchases
                            $totalValue +=  $poQty * $lastPurchase['unit_price'];
                          
                    
                            // If there is remaining quantity from the last purchase, push it back to the stack
                            if ($remainingQty < 0) {
                                $inventoryStack[] = [
                                    'quantity' => $remainingQty,
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'transaction_date' => $lastPurchase['transaction_date'],
                                ];
                            }else{
                  
                            }
                    
                            // Log the purchase details

                            // if()
                         
                    
                        if(abs($lastPurchase['quantity']) > $poQty){
                       
                            if( $lastPurchase['quantity'] > $poQty){
                              
          
                                $logEntry['details'][] = [
                                    'used_qty' => $lastPurchase['quantity'] + $poQty,
                                    'unit_price' => $transaction->unit_price,
                                    'amount' => $poQty * $transaction->unit_price,
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty *  $transaction->unit_price,
                                ];
                            }else{
                                
                            $logEntry['details'][] = [
                                    'used_qty' => -$poQty,
                                    'unit_price' => $transaction->unit_price,
                                    'amount' => $poQty * $transaction->unit_price,
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty *  $transaction->unit_price,
                                ];
                            }

                   
                       
                        }else{
                            if( $lastTransactionStatus == 'Short'){
                                $logEntry['details'][] = [
                                    'used_qty' => $lastPurchase['quantity'],
                                    'unit_price' => $transaction->unit_price,
                                    'amount' => $poQty * $transaction->unit_price,
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty *  $transaction->unit_price,
                                ];
                            }
                        }
                            // PO is fulfilled
                         
                            $poQty =  $remainingQty;
                        } else {
                            // dd(1);
                            // Not enough quantity in this purchase, use all of it and continue to the next one
                            $costOfGoodsPurchased += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                            $poQty -= $lastPurchase['quantity'];
                            $totalQuantity += $lastPurchase['quantity']; // Assuming adding for purchases
                            $totalValue += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                    
                    
                            // Log the purchase details for this part
                            $logEntry['details'][] = [
                                'used_qty' => $lastPurchase['quantity'],
                                'unit_price' => $lastPurchase['unit_price'],
                                'amount' => $lastPurchase['quantity'] * $lastPurchase['unit_price'],
                                'remaining_qty' => 0,
                                'remaining_value' => 0,
                            ];
                      
                        }
                        
                    }
                   
                    // If there is remaining unsold quantity (i.e., poQty > 0), it's an excess purchase
              
                    if ($poQty > 0) {
                        // Create a new entry for the excess purchase
                        $inventoryStack[] = [
                            'quantity' => $poQty,
                            'unit_price' => $lastPurchasePrice,
                            'transaction_date' => $transaction->transaction_date,
                        ];
                       
                        $totalQuantity =  $poQty;  // Assuming adding for purchases
                        $totalValue  =  $poQty * $transaction->unit_price;
                    }
                    else{
                        $totalQuantity =  $poQty;  // Assuming adding for purchases
                        $totalValue =  $poQty * $transaction->unit_price;
                    }

                    // Check for totalValue less than 0 after purchases
                    // if ($totalValue < 0) {
                    //     // Log the negative total value entry into the inventory stack for purchases
                    //     $inventoryStack[] = [
                    //         'quantity' => 0, // No additional quantity since we're just logging a state
                    //         'unit_price' => 0, // No unit price as this is a negative state
                    //         'transaction_date' => $transaction->transaction_date,
                    //         'status' => 'Negative Value', // Custom status for identification
                    //     ];
                    // }
                    
                }
    
                // Log the purchase transaction
                $transactionLogs[] = [
                    'transaction_type' => 'Purchase',
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => $totalValue /$totalQuantity, // Avoid division by zero
                    'cost_of_goods_sold' => 0,
                    'profit_loss' => 0,
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                    'log_amount' => $transaction->quantity * $transaction->unit_price,
                    'inventory_stack' => $inventoryStack,
                    'details' => $logEntry['details'],
                ];

    
                $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';
            } 
            
            elseif (strtolower($transaction->transaction_type) === 'sell') {
        
        
                $sellQtyCheck = abs($transaction->quantity); // Get absolute quantity for selling
                $sellQty = $sellQtyCheck; // Use absolute value without formatting
                $costOfGoodsSold = 0; // Cost of goods sold for this transaction
                $lastSellPrice = $transaction->unit_price;
        

                if($totalQuantity < 0){
                    $inventoryStack[] = [
                        'quantity' => -$transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                $totalQuantity -= $transaction->quantity;
                $totalValue -= $transaction->quantity * $transaction->unit_price;
            
                }
                else{
                    $logEntry = [
                        'transaction_type' => 'Sell',
                        'quantity' => $sellQty,
                        'transaction_date' => $transaction->transaction_date,
                        'selling_price' => $transaction->unit_price,
                        'details' => [],
                        'sell_qty' => $sellQty,
                    ];


                    while ($sellQty > 0 && !empty($inventoryStack)) {
                        // Get the last purchase (LIFO: Last In First Out)
                        $lastPurchase = array_pop($inventoryStack);
                       
                        if ($lastPurchase['quantity'] >= $sellQty) {
                            // Sufficient quantity in the last purchase to fulfill the sale
                            $costOfGoodsSold += $sellQty * $lastPurchase['unit_price'];
                            $remainingQty = $lastPurchase['quantity'] - $sellQty;
                       
                
                            // Update total quantity and value
                            $totalQuantity -= $sellQty;
                       
                            $totalValue -= $sellQty * $lastPurchase['unit_price'];
                
                            // If there is remaining quantity from the last purchase, push it back to the stack
                            if ($remainingQty > 0) {
                                $inventoryStack[] = [
                                    'quantity' => $remainingQty,
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'transaction_date' => $lastPurchase['transaction_date'],
                                ];
                            }
                
                            // Log the sale details
                         
                          
                            if(($totalQuantity > 0)){
                                $logEntry['details'][] = [
                                    'used_qty' => $sellQty,
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'amount' => $sellQty * $lastPurchase['unit_price'],
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty * $lastPurchase['unit_price'],
                                ];
                           
                            }else{
                         
                                $logEntry['details'][] = [
                                    'used_qty' => '',
                                    'unit_price' => '',
                                    'amount' => $sellQty * $lastPurchase['unit_price'],
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty * $lastPurchase['unit_price'],
                                ];
                          
                            }
                  
                
                            // Sale is fulfilled
                            $sellQty = 0;
                        } else {
                            // Not enough quantity in this purchase, use all of it and continue to the next one
                            $costOfGoodsSold += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                            $sellQty -= $lastPurchase['quantity'];
                            $totalQuantity -= $lastPurchase['quantity'];
                            $totalValue -= $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                            // Log the sale details for this part
                            if($totalQuantity > 0){
                                $logEntry['details'][] = [
                                    'used_qty' => $lastPurchase['quantity'],
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'amount' => $lastPurchase['quantity'] * $lastPurchase['unit_price'],
                                    'remaining_qty' => 0,
                                    'remaining_value' => 0,
                                ];
                            }else{
                              
                                $logEntry['details'][] = [
                                    'used_qty' => $lastPurchase['quantity'],
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'amount' => $lastPurchase['quantity'] * $lastPurchase['unit_price'],
                                    'remaining_qty' => 0,
                                    'remaining_value' => 0,
                                ];
                            }
                     
                        }
                    }
        
                    // If there is remaining unsold quantity (i.e., sellQty > 0), it's a short sale
                    if ($sellQty > 0) {
                        // Create a new entry for the short sale
                        $inventoryStack[] = [
                            'quantity' => -$sellQty,
                            'unit_price' => $lastSellPrice,
                            'transaction_date' => $transaction->transaction_date,
                        ];
                        // Update inventory totals with negative quantity
                        $totalQuantity -= $sellQty;  // this will be a negative update
                        $totalValue -= $sellQty * $lastSellPrice; // Account for negative value
                    }
    
                }
    
                // Calculate profit/loss
                $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
                $profitLoss = $totalSaleValue - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;
    
                // Log the sell transaction
                $transactionLogs[] = [
                    'transaction_type' => 'Sell',
                    'sell_qty' => $transaction->quantity,
                    'quantity' => abs($transaction->quantity),
                    'selling_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => $totalValue / $totalQuantity ??  0, // Avoid division by zero
                    'cost_of_goods_sold' => $costOfGoodsSold,
                    'profit_loss' => $profitLoss,
                    'total_profit_loss' => $totalProfitLoss,
                    'details' => $logEntry['details'],
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                    'inventory_stack' => $inventoryStack,
                ];
    
                $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';
            }
        }
    
        // Final calculations
        $finalPrice = ($lastTransactionStatus === 'Long') ?$totalValue / $totalQuantity : $totalValue / $totalQuantity;

    
        return [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue,
            'balance_unit_price' => $totalValue / $totalQuantity ??  0, // Avoid division by zero
            'final_profit_loss' => $totalProfitLoss,
            'last_transaction_status' => $lastTransactionStatus,
            'final_price' => $finalPrice,
            'last_transaction_date' => $lastTransactionDate,
        ];


    }
    



    // ........................................................newLifo.................................................... 

    public function calculateLIFORevised()
    {
        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
        $lastTransaction = $transactions->last();
        $lastTransactionDate = $lastTransaction ? $lastTransaction->transaction_date : null;
        
        $inventoryStack = []; 
        $transactionLogs = []; 
        $totalQuantity = 0; 
        $totalValue = 0; 
        $totalProfitLoss = 0; 
        $lastTransactionStatus = 'N/A'; 
        $lastPurchasePrice = null; 
        $lastSellPrice = null;
    
        foreach ($transactions as $transaction) {
            $item_name = $transaction->item_name;
    
            if (strtolower($transaction->transaction_type) === 'purchase') {

                
                $CurrentPurchasePrice = $transaction->unit_price;
          
                $inventoryStack[] = [
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                ];
    
             
                $totalQuantity += $transaction->quantity;
                $totalValue += $transaction->quantity * $transaction->unit_price;
                $logAmount = $transaction->quantity * $transaction->unit_price;
                
    
             
                $transactionLogs[] = [
                    'transaction_type' => 'Purchase',
                    'quantity' => $transaction->quantity,
                    'last_purchase_price' => $CurrentPurchasePrice,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => $totalValue/$totalQuantity,
                    'cost_of_goods_sold' => 0,
                    'profit_loss' => 0,
                    'log_amount' => $logAmount,
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                    'details' => [[
                        'used_qty' => $transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'purchase_date' => $transaction->transaction_date,
                        'amount' => $transaction->quantity * $transaction->unit_price,
                        'remaining_qty' => 0,
                        'remaining_value' => 0,
                    ]],
                    'inventory_stack' => $inventoryStack, 
                ];
                $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';
                
            } 

            
        }
    
     
        $finalPrice = ($lastTransactionStatus === 'Long') ? $lastPurchasePrice : $lastSellPrice;

    // $transactionLogs[count($transactionLogs) - 2]['last_sell_price']) / 2);
        return [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue,
            'final_profit_loss' => $totalProfitLoss,
            'last_transaction_status' => $lastTransactionStatus,
            'final_price' => $finalPrice,
            'last_transaction_date' => $lastTransactionDate,
        ];
    }
    
    



    public function showLifoReport()
{
    $lifoData = $this->calculateLIFO(); 
    // dd($lifoData); 
 
    return view('inventory_valuation.lifo', $lifoData);  
}

// public function calculateFIFO()
// {
   
//     $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
    
   
//     $firstTransaction = $transactions->first();
//     $firstTransactionDate = $firstTransaction ? $firstTransaction->transaction_date : null;
    
//     $inventoryQueue = []; 
//     $transactionLogs = [];
//     $totalQuantity = 0;
//     $totalValue = 0;
//     $totalProfitLoss = 0;
//     $lastTransactionStatus = 'Long'; 
    
//     foreach ($transactions as $transaction) {
//         $item_name = $transaction->item_name;

//         if (strtolower($transaction->transaction_type) === 'purchase') {
          
//             $inventoryQueue[] = [
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
//                 'status' => $totalQuantity >= 0 ? 'Long' : 'Short',
//                 'details' => [
//                     [
//                         'used_qty' => $transaction->quantity,
//                         'unit_price' => $transaction->unit_price,
//                         'remaining_qty' => 0,
//                         'remaining_value' => 0,
//                     ]
//                 ],
//                 'inventory_queue' => $inventoryQueue, 
//             ];
//             $lastTransactionStatus = $totalQuantity >= 0 ? 'Long' : 'Short';
//         } elseif (strtolower($transaction->transaction_type) === 'sell') {
//             $sellQty = abs($transaction->quantity); 
//             $costOfGoodsSold = 0;

//             $logEntry = [
//                 'transaction_type' => 'Sell',
//                 'quantity' => $sellQty,
//                 'transaction_date' => $transaction->transaction_date,
//                 'selling_price' => $transaction->unit_price,
//                 'details' => [],
//                 'total_amount' => 0,
//             ];
            
//             while ($sellQty > 0 && !empty($inventoryQueue)) {
              
//                 $firstPurchase = array_shift($inventoryQueue);

//                 if ($firstPurchase['quantity'] >= $sellQty) {
                    
//                     $remainingQty = $firstPurchase['quantity'] - $sellQty;
//                     if ($remainingQty > 0) {
                       
//                         $inventoryQueue[] = [
//                             'quantity' => $remainingQty,
//                             'unit_price' => $firstPurchase['unit_price'],
//                             'transaction_date' => $firstPurchase['transaction_date'],
//                         ];
//                     }

                  
//                     $costOfGoodsSold += $sellQty * $firstPurchase['unit_price'];
//                     $logEntry['total_amount'] += $sellQty * $transaction->unit_price;

//                     $logEntry['details'][] = [
//                         'used_qty' => $sellQty,
//                         'unit_price' => $firstPurchase['unit_price'],
//                         'amount' => $sellQty * $firstPurchase['unit_price'],
//                         'remaining_qty' => $remainingQty,
//                         'remaining_value' => $remainingQty * $firstPurchase['unit_price'],
//                     ];

//                     $sellQty = 0; 
//                 } else {
                   
//                     $costOfGoodsSold += $firstPurchase['quantity'] * $firstPurchase['unit_price'];
//                     $logEntry['total_amount'] += $firstPurchase['quantity'] * $transaction->unit_price;

//                     $logEntry['details'][] = [
//                         'used_qty' => $firstPurchase['quantity'],
//                         'unit_price' => $firstPurchase['unit_price'],
//                         'amount' => $firstPurchase['quantity'] * $firstPurchase['unit_price'],
//                         'remaining_qty' => 0,
//                         'remaining_value' => 0,
//                     ];
//                     $sellQty -= $firstPurchase['quantity'];
//                 }
//             }

//             if ($sellQty > 0) {
              
//                 $logEntry['details'][] = [
//                     'used_qty' => 0,
//                     'unit_price' => 0,
//                     'remaining_qty' => -$sellQty,
//                     'short_qty' => -$sellQty,
//                     'remaining_value' => 0,
//                 ];
//                 $totalQuantity -= $sellQty;
//             }

        
//             $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
//             $profitLoss = $totalSaleValue - $costOfGoodsSold;
//             $totalProfitLoss += $profitLoss;

        
//             $transactionLogs[] = [
//                 'transaction_type' => 'Sell',
//                 'quantity' => abs($transaction->quantity),
//                 'selling_price' => $transaction->unit_price,
//                 'actual_sales_value' => abs($transaction->quantity) * $transaction->unit_price,
//                 'unit_sell_price' => (abs($transaction->quantity) * $transaction->unit_price)/abs($transaction->quantity),
//                 'transaction_date' => $transaction->transaction_date,
//                 'balance_qty' => $totalQuantity,
//                 'balance_value' => $totalValue,
//                 'unit_cogs_price' => abs($transaction->quantity) > 0 
//                 ? $costOfGoodsSold / abs($transaction->quantity) 
//                 : 0,
//                 'cost_of_goods_sold' => $costOfGoodsSold,
//                 'profit_loss' => $profitLoss,
//                 'total_profit_loss' => $totalProfitLoss,
//                 'status' => $totalQuantity >= 0 ? 'Long' : 'Short',
//                 'details' => $logEntry['details'],
//                 'inventory_queue' => $inventoryQueue, // Log the current state of the inventory queue after sale
//             ];
//         }
//     }
    
//     // dump($transactionLogs);
    
//     // Determine final price based on the last transaction status
//     if (!empty($inventoryQueue)) {
//         $finalPrice = $lastTransactionStatus === 'Long' 
//                     ? $inventoryQueue[0]['unit_price'] 
//                     : $transactionLogs[count($transactionLogs) - 2]['selling_price'];
//     } else {
//         $finalPrice = 'N/A';
//     }

//     return [
//         'transaction_logs' => $transactionLogs,
//         'final_balance_qty' => $totalQuantity,
//         'final_balance_value' => $totalValue,
//         'final_profit_loss' => $totalProfitLoss,
//         'last_transaction_status' => $lastTransactionStatus,
//         'final_price' => $finalPrice,
//         'first_transaction_date' => $firstTransactionDate,
//     ];
// }

public function calculateFIFO()
{
    // Fetch all transactions ordered by transaction date
    $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
    $lastTransaction = $transactions->last();
    $lastTransactionDate = $lastTransaction ? $lastTransaction->transaction_date : null;

    // Initialize variables for inventory processing
    $inventoryStack = []; // Holds the purchase transactions for FIFO processing
    $transactionLogs = []; // Logs all transaction details for reporting
    $totalQuantity = 0; // Total quantity in inventory
    $totalValue = 0; // Total value of inventory (based on quantity and unit price)
    $totalProfitLoss = 0; // Running total of profit/loss from sales
    $lastPurchasePrice = null; // Last purchase price
    $lastSellPrice = null; // Last sell price
    $lastTransactionStatus = ''; // Status of the inventory (Short/Long)
    $lastBalanceUnitPrice = null;

    foreach ($transactions as $transaction) {
        // Process purchase transactions
        if (strtolower($transaction->transaction_type) === 'purchase') {
            $purchaseQty = abs($transaction->quantity); // Ensure quantity is positive
            $lastPurchasePrice = $transaction->unit_price;

            // Add purchase to the inventory stack
            $inventoryStack[] = [
                'quantity' => $transaction->quantity,
                'unit_price' => $transaction->unit_price,
                'transaction_date' => $transaction->transaction_date,
            ];

            // Update totals
            $totalQuantity += $transaction->quantity;
            $totalValue += $transaction->quantity * $transaction->unit_price;

            // Log the purchase transaction
            $transactionLogs[] = $this->logTransaction(
                'Purchase',
                $transaction,
                $totalQuantity,
                $totalValue,
                0,
                0,
                $inventoryStack
            );

            // Update the last transaction status
            $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';

        // Process sell transactions
        } elseif (strtolower($transaction->transaction_type) === 'sell') {
            $sellQty = abs($transaction->quantity); // Ensure quantity is positive
            $costOfGoodsSold = 0; // Reset COGS for this transaction
            $lastSellPrice = $transaction->unit_price;

            $logEntry = [
                'transaction_type' => 'Sell',
                'quantity' => $sellQty,
                'transaction_date' => $transaction->transaction_date,
                'selling_price' => $transaction->unit_price,
                'details' => [],
                'sell_qty' => $sellQty,
            ];

            // Process sales using FIFO method
            while ($sellQty > 0 && !empty($inventoryStack)) {
                $firstPurchase = array_shift($inventoryStack); // FIFO: Get the first purchase
                // Check if sufficient quantity is available
                if ($firstPurchase['quantity'] >= $sellQty) {
                    // Enough quantity to fulfill the sale
                    $costOfGoodsSold += $sellQty * $firstPurchase['unit_price'];
                    $remainingQty = $firstPurchase['quantity'] - $sellQty;
                    
                    // Update total quantity and value
                    $totalQuantity -= $sellQty;
                    $totalValue -= $sellQty * $firstPurchase['unit_price'];
                    
                    // Log the sale details
                    $logEntry['details'][] = [
                        'used_qty' => $sellQty,
                        'unit_price' => $firstPurchase['unit_price'],
                        'amount' => $sellQty * $firstPurchase['unit_price'],
                        'remaining_qty' => $remainingQty,
                        'remaining_value' => $remainingQty * $firstPurchase['unit_price'],
                    ];
                    
                    // Push back remaining quantity to inventory stack if any
                    if ($remainingQty > 0) {
                        $inventoryStack[] = [
                            'quantity' => $remainingQty,
                            'unit_price' => $firstPurchase['unit_price'],
                            'transaction_date' => $firstPurchase['transaction_date'],
                        ];
                    }
                    
                    // Sale is fulfilled
                    $sellQty = 0;
                    
                } else {
                    // Not enough quantity in this purchase
                    $costOfGoodsSold += $firstPurchase['quantity'] * $firstPurchase['unit_price'];
                    $sellQty -= $firstPurchase['quantity'];
                    $totalQuantity -= $firstPurchase['quantity'];
                    $totalValue -= $firstPurchase['quantity'] * $firstPurchase['unit_price'];
                    
                    // Log the sale details for this part
                    $logEntry['details'][] = [
                        'used_qty' => $firstPurchase['quantity'],
                        'unit_price' => $firstPurchase['unit_price'],
                        'amount' => $firstPurchase['quantity'] * $firstPurchase['unit_price'],
                        'remaining_qty' => 0,
                        'remaining_value' => 0,
                    
                    ];
                    // dd($logEntry);
                }
            }
            
            // Handle short sales
            if ($sellQty > 0) {
                
                $inventoryStack[] = [
                    'quantity' => -$sellQty,
                    'unit_price' => $lastSellPrice,
                    'transaction_date' => $transaction->transaction_date,
                ];
                
                $totalQuantity -= $sellQty; // Update with negative quantity
                $totalValue -= $sellQty * $lastSellPrice; // Account for negative value
            }

            // Calculate profit/loss
            $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
            $profitLoss = $totalSaleValue - $costOfGoodsSold;
            $totalProfitLoss += $profitLoss;

            // Log the sell transaction
            $transactionLogs[] = $this->logTransaction(
                'Sell',
                $transaction,
                $totalQuantity,
                $totalValue,
                $costOfGoodsSold,
                $profitLoss,
                $inventoryStack,
                $logEntry['details']
            );

            // Update the last transaction status
            $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';
        }
    }

    // Final calculations
    $finalPrice = ($lastTransactionStatus === 'Long') ? $lastPurchasePrice : $lastSellPrice;

    return [
        'transaction_logs' => $transactionLogs,
        'final_balance_qty' => $totalQuantity,
        'final_balance_value' => $totalValue,
        'final_profit_loss' => $totalProfitLoss,
        'last_transaction_status' => $lastTransactionStatus,
        'balance_unit_price' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0, // Avoid division by zero
        'final_price' => $finalPrice,
        'last_transaction_date' => $lastTransactionDate,
    ];
}

/**
 * Logs the details of a transaction
 */
private function logTransaction($type, $transaction, $totalQuantity, $totalValue, $costOfGoodsSold, $profitLoss, $inventoryStack, $details = [])
{
    return [
        'transaction_type' => $type,
        'quantity' => $transaction->quantity,
        'unit_price' => $transaction->unit_price,
        'transaction_date' => $transaction->transaction_date,
        'balance_qty' => $totalQuantity,
        'balance_value' => $totalValue,
        'balance_unit_price' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0, // Avoid division by zero
      
        'cost_of_goods_sold' => $costOfGoodsSold,
        'profit_loss' => $profitLoss,
        'details' => $details,
        'status' => $totalQuantity < 0 ? 'Short' : 'Long',
        'inventory_queue' => $inventoryStack,
    ];
}




    
    public function calculateTransactionDetails($transactionLogs)
    {
        dd(1);
        $calculatedLogs = [];
        
        // Initialize final summary values
        $finalPurchaseQty = 0;
        $finalSellQty = 0;
        $finalPurchaseValue = 0; // To accumulate total purchase value
        $finalSellValue = 0; // To accumulate total sell value
    
        // Initialize balance quantities and values
        $currentBalanceQty = 0;
        $currentBalanceValue = 0;
    
        foreach ($transactionLogs as $log) {
            // Initialize totals for each log
            $totalPurchaseQty = 0;
            $totalSellQty = 0;
            $totalPurchaseValue = 0; // To accumulate purchase value
            $totalSellValue = 0; // To accumulate sell value
    
            // Check if details are set and is an array
            if (isset($log['details']) && is_array($log['details'])) {
                // Update current balances before processing each log
                $currentBalanceQty = floatval($log['balance_qty'] ?? 0);
                $currentBalanceValue = floatval($log['balance_value'] ?? 0);
    
                foreach ($log['details'] as $detail) {
                    $usedQty = floatval($detail['used_qty'] ?? 0);
                    $unitPrice = floatval($detail['unit_price'] ?? 0);
                    
                    // Check the transaction type and calculate accordingly
                    if ($log['transaction_type'] == 'Purchase') {
                        // Calculate purchase details
                        $purchaseValue = $usedQty * $unitPrice; // Calculate purchase value
                        $totalPurchaseQty += $usedQty;
                        $totalPurchaseValue += $purchaseValue; // Accumulate total purchase value
                        $finalPurchaseQty += $usedQty; // Accumulate for final summary
                        $finalPurchaseValue += $purchaseValue; // Accumulate for final summary
    
                        // Update current balance
                        $currentBalanceQty += $usedQty; 
                        $currentBalanceValue += $purchaseValue;
    
                        // Append detail for Purchase
                        $calculatedLogs[] = [
                            'transaction_date' => $log['transaction_date'],
                            'transaction_type' => 'Purchase',
                            'used_qty' => $usedQty,
                            'unit_price' => $unitPrice,
                            'total_value' => $purchaseValue,
                            'running_purchase_qty' => $totalPurchaseQty,
                            'running_purchase_value' => $totalPurchaseValue,
                            'balance_qty' => $currentBalanceQty, // Update balance
                            'balance_value' => $currentBalanceValue, // Update balance
                            'status' => $log['status'] ?? 'N/A',
                        ];
                        dd($calculatedLogs);
                        
                    } elseif ($log['transaction_type'] == 'Sell') {
                        // Calculate sell details
                        $sellValue = $usedQty * $unitPrice; // Calculate sell value
                        $totalSellQty += $usedQty;
                        $totalSellValue += $sellValue; // Accumulate total sell value
                        $finalSellQty += $usedQty; // Accumulate for final summary
                        $finalSellValue += $sellValue; // Accumulate for final summary
    
                        // Check if we have enough balance before subtracting
                        if ($currentBalanceQty >= $usedQty) {
                            // Update current balance if there's enough stock
                            $currentBalanceQty -= $usedQty; 
                            $currentBalanceValue -= $sellValue;
                        } else {
                            // Log the last known good balance before shortage
                            $calculatedLogs[] = [
                                'transaction_date' => $log['transaction_date'],
                                'transaction_type' => 'Sell',
                                'used_qty' => $usedQty,
                                'unit_price' => $unitPrice,
                                'total_value' => $sellValue,
                                'balance_qty' => $currentBalanceQty, // Balance just before shortage
                                'balance_value' => $currentBalanceValue, // Balance just before shortage
                                'status' => $log['status'] ?? 'N/A',
                            ];
    
                            // Since we don't have enough stock, we can choose to either:
                            // 1. Keep the current balance as is (for the log)
                            // 2. Or, force it to go negative (but only for tracking purpose)
    
                            // Update to negative balance (optional)
                            $currentBalanceQty -= $usedQty; 
                            $currentBalanceValue -= $sellValue; 
                        }
    
                        // Append detail for Sell
                        $calculatedLogs[] = [
                            'transaction_date' => $log['transaction_date'],
                            'transaction_type' => 'Sell',
                            'used_qty' => $usedQty,
                            'unit_price' => $unitPrice,
                            'total_value' => $sellValue,
                            'running_sell_qty' => $totalSellQty,
                            'running_sell_value' => $totalSellValue,
                            'balance_qty' => $currentBalanceQty, // Update balance
                            'balance_value' => $currentBalanceValue, // Update balance
                            'status' => $log['status'] ?? 'N/A',
                        ];
                    }
                }
            }
    
            // Add a summary for totals as a separate entry
            if ($totalPurchaseQty > 0 || $totalSellQty > 0) {
                $calculatedLogs[] = [
                    'transaction_date' => $log['transaction_date'],
                    'transaction_type' => 'Total',
                    'total_purchase_qty' => $totalPurchaseQty,
                    'total_sell_qty' => $totalSellQty,
                    'total_purchase_value' => $totalPurchaseValue,
                    'total_sell_value' => $totalSellValue,
                    'balance_qty' => $currentBalanceQty, // Current balance after this transaction
                    'balance_value' => $currentBalanceValue, // Current balance after this transaction
                    'status' => $log['status'] ?? 'N/A',
                ];
            }
        }
    
        // Add the final summary as the last row
        $calculatedLogs[] = [
            'transaction_date' => 'Final Summary',
            'transaction_type' => 'Summary',
            'total_purchase_qty' => $finalPurchaseQty,
            'total_sell_qty' => $finalSellQty,
            'total_purchase_value' => $finalPurchaseValue,
            'total_sell_value' => $finalSellValue,
            'balance_qty' => $currentBalanceQty, // Final balance after all transactions
            'balance_value' => $currentBalanceValue, // Final balance after all transactions
            'status' => 'N/A',
        ];
    
        return $calculatedLogs;
    }
    
    
    public function getPositionReport()
    {
        $lifoData = $this->calculateLIFO();
        $fifoData = $this->calculateFIFO();
        // dd($lifoData,  $fifoData);
        $avgData = $this->calculateAverage();
        
        return view('inventory_valuation.position_report', compact('lifoData','fifoData','avgData'));
    }

  
    

    public function calculateAverage()
{
   
    $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
    
   
    $totalQuantity = 0;
    $totalValue = 0;
    $totalProfitLoss = 0;
    $transactionLogs = [];
    $item_name = '';

    foreach ($transactions as $transaction) {
        $item_name = $transaction->item_name;

        if (strtolower($transaction->transaction_type) === 'purchase') {
          
            $totalQuantity += $transaction->quantity;
            $totalValue += $transaction->quantity * $transaction->unit_price;

           
            $transactionLogs[] = [
                'transaction_type' => 'Purchase',
                'quantity' => $transaction->quantity,
                'unit_price' => $transaction->unit_price,
                'transaction_date' => $transaction->transaction_date,
                'balance_qty' => $totalQuantity,
                'balance_value' => $totalValue,
                'average_cost' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0,
                'profit_loss' => 0,
                'details' => [[
                    'used_qty' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'remaining_qty' => 0, 
                    'remaining_value' => 0, 
                ]],
            ];
        } elseif (strtolower($transaction->transaction_type) === 'sell') {
            $sellQty = abs($transaction->quantity);
            $costOfGoodsSold = 0;
            $logEntry = [
                'transaction_type' => 'Sell',
                'quantity' => $sellQty,
                'transaction_date' => $transaction->transaction_date,
                'selling_price' => $transaction->unit_price,
                'details' => [],
            ];

            while ($sellQty > 0 && $totalQuantity > 0) {
              
                $averageCost = $totalQuantity > 0 ? $totalValue / $totalQuantity : 0;

                if ($sellQty <= $totalQuantity) {
                   
                    $costOfGoodsSold += $sellQty * $averageCost;
                    $totalQuantity -= $sellQty;
                    $totalValue -= $sellQty * $averageCost; 

                  
                    $logEntry['details'][] = [
                        'used_qty' => $sellQty,
                        'unit_price' => $averageCost,
                        'remaining_qty' => 0, 
                        'remaining_value' => 0, 
                    ];
                    $sellQty = 0; 
                } else {
                  
                    $costOfGoodsSold += $totalQuantity * $averageCost;
                    $sellQty -= $totalQuantity; 
                    $totalValue = 0; 
                    $totalQuantity = 0; 

                  
                    $logEntry['details'][] = [
                        'used_qty' => $totalQuantity,
                        'unit_price' => $averageCost,
                        'remaining_qty' => 0,
                        'remaining_value' => 0,
                    ];
                }
            }

            // Calculate profit/loss for this transaction
            $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
            $profitLoss = $totalSaleValue - $costOfGoodsSold;
            $totalProfitLoss += $profitLoss;

            // Log the sell details
            $transactionLogs[] = [
                'transaction_type' => 'Sell',
                'quantity' => abs($transaction->quantity),
                'selling_price' => $transaction->unit_price,
                'transaction_date' => $transaction->transaction_date,
                'balance_qty' => $totalQuantity,
                'balance_value' => $totalValue,
                'cost_of_goods_sold' => $costOfGoodsSold,
                'profit_loss' => $profitLoss,
                'total_profit_loss' => $totalProfitLoss,
                'average_cost' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0,
                'details' => $logEntry['details'],
            ];
        }
        // dump($transactionLogs);
    }

    // Return the calculated average data as an array
    return [
        'transaction_logs' => $transactionLogs,
        'final_balance_qty' => $totalQuantity,
        'final_balance_value' => $totalValue,
        'final_profit_loss' => $totalProfitLoss,
        'item_name' => $item_name,
    ];
}

    

public function showAverageReport()
{
    
    $averageData = $this->calculateAverage();  
//  dd($averageData);
    return view('inventory_valuation.average', $averageData);  
}

    
    

    public function showFifoReport()
    {
        
        $fifoData = $this->calculateFIFO();  
    //  dd($fifoData);
        return view('inventory_valuation.fifo', $fifoData);  
    }
    
    

        public function filter(Request $request)
        {
            $filterType = $request->input('filter_type');
            $date = $request->input('date');
            $inventory = InventoryTransaction::query();

            if ($filterType && $date) {
                switch ($filterType) {
                    case 'daily':
                        $inventory->whereDate('transaction_date', $date);
                        break;

                    case 'weekly':
                        $startOfWeek = Carbon::parse($date)->startOfWeek();
                        $endOfWeek = Carbon::parse($date)->endOfWeek();
                        $inventory->whereBetween('transaction_date', [$startOfWeek, $endOfWeek]);
                        break;

                    case 'monthly':
                        $inventory->whereMonth('transaction_date', Carbon::parse($date)->month)
                                ->whereYear('transaction_date', Carbon::parse($date)->year);
                        break;
                }
            }

            $inventory = $inventory->get();

            return view('inventory_valuation.index', compact('inventory'));
        }

    


    

    public function store_inventory(Request $request)
    {
       

      
        $transaction = new InventoryTransaction();
        $transaction->transaction_type = $request->input('type'); // Either Purchase or Sell
        $transaction->company_name = $request->input('company_name');
        $transaction->item_name = $request->input('item_name');
        $transaction->quantity = $request->input('quantity');
        $transaction->unit_price = $request->input('price');
        $transaction->transaction_date = Carbon::now();
        $transaction->position = 'open';
        
// dd($transaction);
        $transaction->save();

    
        return redirect()->back()->with('success', 'Transaction saved successfully!');
    }


    public function get_inventory_list(Request $request)
{
   
    $filterTodate = $request->filterTodate;
    $filterFromdate = $request->filterFromdate;
    $filterType = $request->filterType;

  
    $query = InventoryTransaction::join('companies', 'inventory_transactions.company_name', '=', 'companies.company_name')->select('inventory_transactions.*', 'companies.company_name');
    
    if ($filterFromdate && $filterTodate) {
        
        $query->whereBetween('inventory_transactions.transaction_date', [$filterTodate, $filterFromdate]);
    }
    
    
    $filteredDatas = $query->get();

    $data = [];
    foreach ($filteredDatas as $filteredData) {
        $tempData = [
            'transaction_date' => date('d-M-Y', strtotime($filteredData->transaction_date)),
            'transaction_type' => $filteredData->transaction_type ?? '',
            'unit_price' => $filteredData->unit_price ?? '',
            'quantity' => $filteredData->quantity ?? '',
            'item_name' => $filteredData->item_name ?? '',
            'company_name' => $filteredData->company_name ?? '',

            
        ];
        $data[] = $tempData;
    }
    
 
    return response()->json($data);
}

public function getValuationData(Request $request)
{
    $valuationType = $request->input('valuationType');
    
    // Fetch inventory transactions, ordered by transaction date
    $items = InventoryTransaction::select('item_name', 'transaction_type', 'quantity', 'unit_price', 'transaction_date')
        ->orderBy('transaction_date', 'asc')  // Order by oldest first for FIFO
        ->get()
        ->groupBy('item_name');  // Group transactions by item for individual valuation

    $valuationData = [];

    foreach ($items as $itemName => $transactions) {
        $totalQuantity = 0;
        $totalValue = 0;
        $inventoryStack = [];

        if ($valuationType === 'lifo') {
            // LIFO calculation (most recent purchases sold first)
            foreach ($transactions as $transaction) {
                if (strtolower($transaction->transaction_type) === 'purchase') {
                    $inventoryStack[] = [
                        'quantity' => $transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                    $totalQuantity += $transaction->quantity;
                    $totalValue += $transaction->quantity * $transaction->unit_price;
                } elseif (strtolower($transaction->transaction_type) === 'sell') {
                    $sellQty = abs($transaction->quantity);
                    while ($sellQty > 0 && !empty($inventoryStack)) {
                        $lastPurchase = array_pop($inventoryStack);

                        if ($lastPurchase['quantity'] >= $sellQty) {
                            $totalQuantity -= $sellQty;
                            $totalValue -= $sellQty * $lastPurchase['unit_price'];
                            $remainingQty = $lastPurchase['quantity'] - $sellQty;

                            if ($remainingQty > 0) {
                                $inventoryStack[] = [
                                    'quantity' => $remainingQty,
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'transaction_date' => $lastPurchase['transaction_date'],
                                ];
                            }
                            $sellQty = 0;
                        } else {
                            $totalQuantity -= $lastPurchase['quantity'];
                            $totalValue -= $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                            $sellQty -= $lastPurchase['quantity'];
                        }
                    }
                }
            }
        } elseif ($valuationType === 'fifo') {
            // FIFO calculation (oldest purchases sold first)
            foreach ($transactions as $transaction) {
                if (strtolower($transaction->transaction_type) === 'purchase') {
                    $inventoryStack[] = [
                        'quantity' => $transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                    $totalQuantity += $transaction->quantity;
                    $totalValue += $transaction->quantity * $transaction->unit_price;
                } elseif (strtolower($transaction->transaction_type) === 'sell') {
                    $sellQty = abs($transaction->quantity);
                    while ($sellQty > 0 && !empty($inventoryStack)) {
                        $firstPurchase = array_shift($inventoryStack);  // Use FIFO logic

                        if ($firstPurchase['quantity'] >= $sellQty) {
                            $totalQuantity -= $sellQty;
                            $totalValue -= $sellQty * $firstPurchase['unit_price'];
                            $remainingQty = $firstPurchase['quantity'] - $sellQty;

                            if ($remainingQty > 0) {
                                $inventoryStack[] = [
                                    'quantity' => $remainingQty,
                                    'unit_price' => $firstPurchase['unit_price'],
                                    'transaction_date' => $firstPurchase['transaction_date'],
                                ];
                            }
                            $sellQty = 0;
                        } else {
                            $totalQuantity -= $firstPurchase['quantity'];
                            $totalValue -= $firstPurchase['quantity'] * $firstPurchase['unit_price'];
                            $sellQty -= $firstPurchase['quantity'];
                        }
                    }
                }
            }
        } elseif ($valuationType === 'average') {
            // Average cost calculation
            $totalPurchasedQuantity = 0;
            $totalPurchasedValue = 0;
            
            foreach ($transactions as $transaction) {
                if (strtolower($transaction->transaction_type) === 'purchase') {
                    $totalPurchasedQuantity += $transaction->quantity;
                    $totalPurchasedValue += $transaction->quantity * $transaction->unit_price;

                    $totalQuantity += $transaction->quantity;
                    $totalValue += $transaction->quantity * $transaction->unit_price;
                } elseif (strtolower($transaction->transaction_type) === 'sell') {
                    $sellQty = abs($transaction->quantity);
                    $averageCost = $totalPurchasedQuantity > 0 ? $totalPurchasedValue / $totalPurchasedQuantity : 0;

                    $totalQuantity -= $sellQty;
                    $totalValue -= $sellQty * $averageCost;

                    // If sold quantity is greater than available stock, update accordingly
                    if ($totalQuantity < 0) {
                        // Show short status and adjust value accordingly
                        $totalValue = abs($totalQuantity) * $averageCost; // Calculate the value for the short quantity
                        $totalQuantity = 0; // Set quantity to zero
                    }
                }
            }

            // Calculate final average unit price for the current inventory
            $averageUnitPrice = $totalQuantity > 0 ? $totalValue / $totalQuantity : 0;
        }

        // Determine stock status
        $status = 'Neutral';
        if ($totalQuantity < 0) {
            $status = 'Short';
        } elseif ($totalQuantity > 0) {
            $status = 'Long';
        }

        // Append calculated data for each valuation type
        $valuationData[] = [
            'item_name' => $itemName,
            'quantity' => $totalQuantity,
            'unit_price' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0,  // Avoid division by zero
            'total_value' => $totalValue,
            'status' => $status,  // Track stock position
            'last_sell_value' => $sellQty > 0 ? ($totalValue / abs($transaction->quantity)) : 0, // Last sell value if there's a short
        ];
    }

    return response()->json($valuationData);
}






    public function getTransactionDetails()
    {
        // dd(1);
        // Example of fetching transaction logs from a model or other source
        $transactionLogs = InventoryTransaction::all(); // Replace with your actual model/data source
// dd($transactionLogs);
        // Call the calculateTransactionDetails method
        $calculatedLogs = $this->calculateTransactionDetails($transactionLogs);

        // Return the view with the calculated data
        return view('inventory_valuation.valuation', compact('calculatedLogs'));
    }


    
    public function get_position_report_list(Request $request)
{
   
    $filterTodate = $request->filterTodate;
    $filterFromdate = $request->filterFromdate;
    $filterType = $request->filterType;

    $lifoData = $this->calculateLIFO($filterFromdate, $filterTodate);
    $fifoData = $this->calculateFIFO($filterFromdate, $filterTodate);
    // dd($lifoData,  $fifoData);
    $avgData = $this->calculateAverage($filterFromdate, $filterTodate);
 

    $data = [];
  
        $tempData = [
            'last_transaction_date' =>  $lifoData['last_transaction_date'] ?? 'N/A' ,
            'item_name' => $lifoData['item_name'] ?? 'N/A' ,
            'final_balance_qty' => $lifoData['final_balance_qty'] ?? 'N/A',
            'quantity' => $lifoData['final_balance_value'] ?? 'N/A' ,
            'item_name' => $fifoData['final_balance_value'] ?? 'N/A',
            'company_name' => $fifoData['final_balance_value'] ?? 'N/A',
            'company_name' => $lifoData['manual_match'] ?? 'N/A',
            'company_name' => $lifoData['netwise'] ?? 'N/A',
        ];
        $data[] = $tempData;

    
    
 
    return response()->json($data);
}





    
}
