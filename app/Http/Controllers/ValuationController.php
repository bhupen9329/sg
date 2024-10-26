<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryTransaction;
use App\Models\Company;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Facades\View;


class ValuationController extends Controller
{
    public function index()
    {
        $inventory = InventoryTransaction::all();
        $companies = Company::all(); // Retrieve all companies

        // dd($companies);
        return view('inventory_valuation.index', compact('inventory', 'companies'));
    }


    public function calculateLIFO($id = null,  $item_id = null)
    {
        if ($id && $item_id ) {
            $transaction_data = InventoryTransaction::where('id', $id)->select('transaction_date')->first();
            // $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

            // Get all transactions up to and including the specific transaction_date
            $transactions = InventoryTransaction::where('transaction_date', '<=', $transaction_data->transaction_date)
                ->where('item_id', $item_id)
                ->orderBy('transaction_date', 'asc')
                ->get();
        }elseif( $item_id){
            $transactions = InventoryTransaction::
              where('item_id', $item_id)
            ->orderBy('transaction_date', 'asc')
            ->get();
        }
        
        else {
            $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')
                ->get();
        }


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



                if ($lastTransactionStatus == 'Long' ||  $lastTransactionStatus == '') {

                    $inventoryStack[] = [
                        'quantity' => $transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                    // Update inventory totals
                    $totalQuantity += $transaction->quantity;
                    $totalValue += $transaction->quantity * $transaction->unit_price;
                } else {
                    $lastPurchaseTotal = 0;
                    $lastPurchaseQty = 0;
                    while ($poQty > 0 && !empty($inventoryStack)) {
                        // Get the last purchase (LIFO: Last In First Out)
                        $lastPurchase = array_pop($inventoryStack);
                        $lasttotalValue = ($lastPurchase['quantity'] * $lastPurchase['unit_price']);
                        $lastPurchaseTotal +=  $lasttotalValue;
                        $lastPurchaseQty +=  $lastPurchase['quantity'];

                        if ($lastPurchase['quantity'] <= $poQty) {
                            // Sufficient quantity in the last purchase to fulfill the PO
                            $costOfGoodsPurchased = $poQty * $lastPurchase['unit_price'];
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
                            } else {
                            }

                            // Log the purchase details

                            // if()

                            if (abs($lastPurchase['quantity']) > $poQty) {

                                if ($lastPurchase['quantity'] > $poQty) {
                                    $logEntry['details'][] = [
                                        'used_qty' => $lastPurchase['quantity'] + $poQty,
                                        'unit_price' => $transaction->unit_price,
                                        'amount' => $poQty * $transaction->unit_price,
                                        'remaining_qty' => $remainingQty,
                                        'remaining_value' => $remainingQty *  $transaction->unit_price,
                                    ];
                                } else {

                                    $logEntry['details'][] = [
                                        'used_qty' => -$poQty,
                                        'unit_price' => $transaction->unit_price,
                                        'amount' => $poQty * $transaction->unit_price,
                                        'remaining_qty' => $remainingQty,
                                        'remaining_value' => $remainingQty *  $transaction->unit_price,
                                    ];
                                }
                            } else {
                                if ($lastTransactionStatus == 'Short') {
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
                    // dump($lastbalance);

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
                    } else {

                        $latestInventory = end($inventoryStack);
                        $totalQuantity = $latestInventory['quantity'] ?? 0;
                        $totalValue = ($latestInventory['quantity'] ?? 0) * ($latestInventory['unit_price'] ?? 0);
                        // $totalQuantity =  $poQty;  // Assuming adding for purchases
                        // $totalValue =  $poQty * $transaction->unit_price;
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
                    'transaction_id' => $transaction->id,
                    'quantity' => $transaction->quantity,
                    'item_name' => $transaction->item_name,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0,
                    'cost_of_goods_sold' => $costOfGoodsPurchased,
                    'profit_loss' => 0,
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                    'log_amount' => $transaction->quantity * $transaction->unit_price,
                    'inventory_stack' => $inventoryStack,
                    'details' => $logEntry['details'] ?? 'No details provided',
                    'lastPurchaseTotal' => $lastPurchaseTotal ?? 0,
                    'lastPurchaseQty' => $lastPurchaseQty ?? 0,
                    'lastbalancePurchase' => ($lastPurchaseQty ?? 0) != 0 ? ($lastPurchaseTotal ?? 0) / $lastPurchaseQty : 0
                ];
                // dump($transactionLogs);

                $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';
            } elseif (strtolower($transaction->transaction_type) === 'sell') {

                $sellQtyCheck = abs($transaction->quantity); // Get absolute quantity for selling
                $sellQty = $sellQtyCheck; // Use absolute value without formatting
                $costOfGoodsSold = 0; // Cost of goods sold for this transaction
                $lastSellPrice = $transaction->unit_price;

                // dump($sellQty);
                $logEntry = [
                    'transaction_type' => 'Sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $transaction->unit_price,
                    'details' => [],
                    'sell_qty' => $sellQty,
                ];


                if ($totalQuantity < 0) {
                    $inventoryStack[] = [
                        'quantity' => -$transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                    $logEntry['details'][] = [
                        'used_qty' => 0,
                        'unit_price' => 0,
                        'amount' => 0,
                        'remaining_qty' => 0,
                        'remaining_value' => 0,
                    ];

                    $totalQuantity -= $transaction->quantity;
                    $totalValue -= $transaction->quantity * $transaction->unit_price;
                } else {

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


                            if (($totalQuantity > 0)) {
                                $logEntry['details'][] = [
                                    'used_qty' => $sellQty,
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'amount' => $sellQty * $lastPurchase['unit_price'],
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty * $lastPurchase['unit_price'],
                                ];
                            } elseif ($totalQuantity < 0) {
                                $logEntry['details'][] = [
                                    'used_qty' => '',
                                    'unit_price' => '',
                                    'amount' => $sellQty * $lastPurchase['unit_price'],
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty * $lastPurchase['unit_price'],
                                ];
                            } else {
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
                            if ($totalQuantity > 0) {
                                $logEntry['details'][] = [
                                    'used_qty' => $lastPurchase['quantity'],
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'amount' => $lastPurchase['quantity'] * $lastPurchase['unit_price'],
                                    'remaining_qty' => 0,
                                    'remaining_value' => 0,
                                ];
                            } else {

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
                    'transaction_id' => $transaction->id,
                    'item_name' => $transaction->item_name,
                    'sell_qty' => $transaction->quantity,
                    'quantity' => abs($transaction->quantity),
                    'selling_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0, // Avoid division by zero
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
        // $finalPrice = ($lastTransactionStatus === 'Long') ? $totalValue / $totalQuantity : $totalValue / $totalQuantity;
        $finalPrice = (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0;


        // Final calculations
        // $finalPrice = ($lastTransactionStatus === 'Long') ? $totalValue / $totalQuantity : $totalValue / $totalQuantity;

        return [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue,
            'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0, // Avoid division by zero
            'final_profit_loss' => $totalProfitLoss,
            'last_transaction_status' => $lastTransactionStatus,
            'final_price' => $finalPrice,
            'last_transaction_date' => $lastTransactionDate,

        ];
    }

    // public function showLifoReport($id)
    // {
    //     $lifoData = $this->calculateLIFO($id);


    //     return view('inventory_valuation.lifo', $lifoData);
    // }


    public function calculateFIFO($id = null, $item_id = null)
    {

        // $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
        if ($id) {
            $transaction_data = InventoryTransaction::where('id', $id)->select('transaction_date')->first();

            // $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

            // Get all transactions up to and including the specific transaction_date
            $transactions = InventoryTransaction::where('transaction_date', '<=', $transaction_data->transaction_date)
                ->where('item_id', $item_id)
                ->orderBy('transaction_date', 'asc')
                ->get();
            // dd($transactions);
        } else {
            $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')
                ->get();
        }
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



                if ($lastTransactionStatus == 'Long' ||  $lastTransactionStatus == '') {

                    $inventoryStack[] = [
                        'quantity' => $transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];
                    // dump($inventoryStack);

                    // Update inventory totals
                    $totalQuantity += $transaction->quantity;
                    $totalValue += $transaction->quantity * $transaction->unit_price;
                } else {
                    $lastPurchaseTotal = 0;
                    $lastPurchaseQty = 0;
                    while ($poQty > 0 && !empty($inventoryStack)) {
                        // Get the last purchase (LIFO: Last In First Out)
                        $lastPurchase = array_shift($inventoryStack);
                        // $lasttotalValue = ($lastPurchase['quantity'] * $lastPurchase['unit_price']);
                        // $lastPurchaseTotal +=  $lasttotalValue;
                        $lasttotalValue = ($lastPurchase['quantity'] * $lastPurchase['unit_price']);
                        $lastPurchaseTotal +=  $lasttotalValue;
                        $lastPurchaseQty +=  $lastPurchase['quantity'];


                        if ($lastPurchase['quantity'] <= $poQty) {
                            // Sufficient quantity in the last purchase to fulfill the PO
                            $costOfGoodsPurchased = $poQty * $lastPurchase['unit_price'];
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
                            } else {
                            }

                            // Log the purchase details

                            // if()


                            if (abs($lastPurchase['quantity']) > $poQty) {

                                if ($lastPurchase['quantity'] > $poQty) {
                                    $logEntry['details'][] = [
                                        'used_qty' => $lastPurchase['quantity'] + $poQty,
                                        'unit_price' => $transaction->unit_price,
                                        'amount' => $poQty * $transaction->unit_price,
                                        'remaining_qty' => $remainingQty,
                                        'remaining_value' => $remainingQty *  $transaction->unit_price,
                                    ];
                                } else {

                                    $logEntry['details'][] = [
                                        'used_qty' => -$poQty,
                                        'unit_price' => $transaction->unit_price,
                                        'amount' => $poQty * $transaction->unit_price,
                                        'remaining_qty' => $remainingQty,
                                        'remaining_value' => $remainingQty *  $transaction->unit_price,
                                    ];
                                }
                            } else {
                                if ($lastTransactionStatus == 'Short') {
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
                    } else {
                        $latestInventory = end($inventoryStack);
                        $totalQuantity = $latestInventory['quantity'] ?? 0;
                        $totalValue = ($latestInventory['quantity'] ?? 0) * ($latestInventory['unit_price'] ?? 0);
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
                    'transaction_id' => $transaction->id,
                    'item_name' => $transaction->item_name,
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0, // Avoid division by zero
                    // Avoid division by zero
                    'cost_of_goods_sold' => $costOfGoodsPurchased,
                    'profit_loss' => 0,
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                    'log_amount' => $transaction->quantity * $transaction->unit_price,
                    'inventory_stack' => array_reverse($inventoryStack),
                    'details' => $logEntry['details'],
                    'lastPurchaseTotal' =>  $lastPurchaseTotal ?? 0,
                    'lastPurchaseQty' => $lastPurchaseQty ?? 0,
                    'lastbalancePurchase' => ($lastPurchaseQty ?? 0) != 0 ? ($lastPurchaseTotal ?? 0) / $lastPurchaseQty : 0
                ];


                $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';
            } elseif (strtolower($transaction->transaction_type) === 'sell') {

                $sellQtyCheck = abs($transaction->quantity); // Get absolute quantity for selling
                $sellQty = $sellQtyCheck; // Use absolute value without formatting
                $costOfGoodsSold = 0; // Cost of goods sold for this transaction
                $lastSellPrice = $transaction->unit_price;

                // dump($sellQty);
                $logEntry = [
                    'transaction_type' => 'Sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $transaction->unit_price,
                    'details' => [],
                    'sell_qty' => $sellQty,
                ];


                if ($totalQuantity < 0) {
                    $inventoryStack[] = [
                        'quantity' => -$transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                    $logEntry['details'][] = [
                        'used_qty' => 0,
                        'unit_price' => 0,
                        'amount' => 0,
                        'remaining_qty' => 0,
                        'remaining_value' => 0,
                    ];

                    $totalQuantity -= $transaction->quantity;
                    $totalValue -= $transaction->quantity * $transaction->unit_price;
                } else {

                    while ($sellQty > 0 && !empty($inventoryStack)) {

                        // Get the last purchase (LIFO: Last In First Out)
                        $lastPurchase = array_shift($inventoryStack);

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


                            if (($totalQuantity > 0)) {
                                $logEntry['details'][] = [
                                    'used_qty' => $sellQty,
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'amount' => $sellQty * $lastPurchase['unit_price'],
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty * $lastPurchase['unit_price'],
                                ];
                            } elseif ($totalQuantity < 0) {
                                $logEntry['details'][] = [
                                    'used_qty' => '',
                                    'unit_price' => '',
                                    'amount' => $sellQty * $lastPurchase['unit_price'],
                                    'remaining_qty' => $remainingQty,
                                    'remaining_value' => $remainingQty * $lastPurchase['unit_price'],
                                ];
                            } else {
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
                            if ($totalQuantity > 0) {
                                $logEntry['details'][] = [
                                    'used_qty' => $lastPurchase['quantity'],
                                    'unit_price' => $lastPurchase['unit_price'],
                                    'amount' => $lastPurchase['quantity'] * $lastPurchase['unit_price'],
                                    'remaining_qty' => 0,
                                    'remaining_value' => 0,
                                ];
                            } else {

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
                    'transaction_id' => $transaction->id,
                    'item_name' => $transaction->item_name,
                    'sell_qty' => $transaction->quantity,
                    'quantity' => abs($transaction->quantity),
                    'selling_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0, // Avoid division by zero

                    'cost_of_goods_sold' => $costOfGoodsSold,
                    'profit_loss' => $profitLoss,
                    'total_profit_loss' => $totalProfitLoss,
                    'details' => $logEntry['details'],
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                    'inventory_stack' => array_reverse($inventoryStack),
                ];

                $lastTransactionStatus = $totalQuantity < 0 ? 'Short' : 'Long';
            }
        }

        // Final calculations
        // $finalPrice = ($lastTransactionStatus === 'Long') ?$totalValue / $totalQuantity : $totalValue / $totalQuantity;
        $finalPrice = (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0;

        return [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue,
            'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0, // Avoid division by zero

            'final_profit_loss' => $totalProfitLoss,
            'last_transaction_status' => $lastTransactionStatus,
            'final_price' => $finalPrice,
            'last_transaction_date' => $lastTransactionDate,

        ];
    }










    public function getPositionReport()
    {
        $categories = Category::all();
        $avgData = $this->calculateAverage();
        $inventory_transaction = InventoryTransaction::orderBy('transaction_date', 'asc')->get();
    
        $lifo_transaction = [];
        $fifo_transaction = [];
        $lifoData = '';
        $fifoData = '';
    
        foreach ($inventory_transaction as $data) {
            $lifoData = $this->calculateLIFO($data->id, $data->item_id);
            $fifoData = $this->calculateFIFO($data->id, $data->item_id);
    
            // Push only the last element if it exists
            if (isset($lifoData['transaction_logs']) && is_array($lifoData['transaction_logs'])) {
                $lifo_transaction[] = end($lifoData['transaction_logs']); // Get the last transaction log
            }
    
            if (isset($fifoData['transaction_logs']) && is_array($fifoData['transaction_logs'])) {
                $fifo_transaction[] = end($fifoData['transaction_logs']); // Get the last transaction log
            }
        }
    
        return view('inventory_valuation.position_report', compact('categories', 'inventory_transaction', 'lifoData', 'fifoData', 'avgData', 'lifo_transaction', 'fifo_transaction'));
    }
    




    // public function calculateAverage()
    // {

    //     $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();


    //     $totalQuantity = 0;
    //     $totalValue = 0;
    //     $totalProfitLoss = 0;
    //     $transactionLogs = [];
    //     $item_name = '';

    //     foreach ($transactions as $transaction) {
    //         $item_name = $transaction->item_name;

    //         if (strtolower($transaction->transaction_type) === 'purchase') {

    //             $totalQuantity += $transaction->quantity;
    //             $totalValue += $transaction->quantity * $transaction->unit_price;


    //             $transactionLogs[] = [
    //                 'transaction_type' => 'Purchase',
    //                 'quantity' => $transaction->quantity,
    //                 'unit_price' => $transaction->unit_price,
    //                 'transaction_date' => $transaction->transaction_date,
    //                 'balance_qty' => $totalQuantity,
    //                 'balance_value' => $totalValue,
    //                 'average_cost' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0,
    //                 'profit_loss' => 0,
    //                 'details' => [[
    //                     'used_qty' => $transaction->quantity,
    //                     'unit_price' => $transaction->unit_price,
    //                     'remaining_qty' => 0,
    //                     'remaining_value' => 0,
    //                 ]],
    //             ];
    //             dump($transactionLogs);
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

    //             while ($sellQty > 0 && $totalQuantity > 0) {

    //                 $averageCost = $totalQuantity > 0 ? $totalValue / $totalQuantity : 0;

    //                 if ($sellQty <= $totalQuantity) {

    //                     $costOfGoodsSold += $sellQty * $averageCost;
    //                     $totalQuantity -= $sellQty;
    //                     $totalValue -= $sellQty * $averageCost;


    //                     $logEntry['details'][] = [
    //                         'used_qty' => $sellQty,
    //                         'unit_price' => $averageCost,
    //                         'remaining_qty' => 0,
    //                         'remaining_value' => 0,
    //                     ];
    //                     $sellQty = 0;
    //                 } else {

    //                     $costOfGoodsSold += $totalQuantity * $averageCost;
    //                     $sellQty -= $totalQuantity;
    //                     $totalValue = 0;
    //                     $totalQuantity = 0;


    //                     $logEntry['details'][] = [
    //                         'used_qty' => $totalQuantity,
    //                         'unit_price' => $averageCost,
    //                         'remaining_qty' => 0,
    //                         'remaining_value' => 0,
    //                     ];
    //                 }
    //             }

    //             // Calculate profit/loss for this transaction
    //             $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
    //             $profitLoss = $totalSaleValue - $costOfGoodsSold;
    //             $totalProfitLoss += $profitLoss;

    //             // Log the sell details
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
    //                 'average_cost' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0,
    //                 'details' => $logEntry['details'],
    //             ];
    //         }
    //         // dump($transactionLogs);
    //     }

    //     // Return the calculated average data as an array
    //     return [
    //         'transaction_logs' => $transactionLogs,
    //         'final_balance_qty' => $totalQuantity,
    //         'final_balance_value' => $totalValue,
    //         'final_profit_loss' => $totalProfitLoss,
    //         'item_name' => $item_name,
    //     ];
    // }


    public function calculateAverage()
    {
        // Retrieve all transactions ordered by the transaction date
        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

        // Initialize variables for totals and transaction logs
        $totalQuantity = 0;  // Total inventory quantity
        $totalValue = 0;     // Total inventory value (quantity * cost)
        $totalProfitLoss = 0; // Overall profit/loss from sales
        $transactionLogs = []; // Log each transaction details
        $item_name = ''; // Keep track of the item name

        // Array to store the remaining quantity and value of each batch
        $inventoryStack = [];

        // Loop through each transaction
        foreach ($transactions as $transaction) {
            // Get the item name from the transaction (assuming all transactions relate to the same item)
            $item_name = $transaction->item_name;

            // Handle purchase transactions
            if (strtolower($transaction->transaction_type) === 'purchase') {
                // Update total inventory quantity and value with the new purchase
                $totalQuantity += $transaction->quantity;
                $totalValue += $transaction->quantity * $transaction->unit_price;

                // Add the new purchase to inventory batches
                $inventoryStack[] = [
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                ];

                // Log the purchase details
                $transactionLogs[] = [
                    'transaction_type' => 'Purchase',
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'average_cost' => $totalQuantity > 0 ? $totalValue / $totalQuantity : 0,
                    'profit_loss' => 0, // No profit/loss on purchases
                    'details' => [[
                        'used_qty' => 0,
                        'remaining_qty' => $transaction->quantity,
                        'unit_price' => $transaction->unit_price,
                    ]],
                    'inventory_stack' => $inventoryStack,
                    'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0,
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                ];
            } elseif (strtolower($transaction->transaction_type) === 'sell') {
                // For sales transactions
                $sellQty = abs($transaction->quantity);
                $costOfGoodsSold = 0; // Track the COGS for this sale
                $totalUsedQty = 0; // Total quantity used in this sale
                $logEntry = [
                    'transaction_type' => 'Sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $transaction->unit_price,
                    'details' => [], // Log how the inventory is being sold
                ];

                // Process the sale using inventory batches (FIFO method)
                while ($sellQty > 0 && count($inventoryStack) > 0) {
                    $batch = array_shift($inventoryStack); // Get the first batch (FIFO method)

                    // Check if the sale quantity can be fulfilled from this batch
                    if ($sellQty <= $batch['quantity']) {
                        // If the sale quantity is less than or equal to the batch quantity
                        $costOfGoodsSold += $sellQty * $batch['unit_price'];
                        $totalUsedQty += $sellQty; // Track total used quantity
                        $batch['quantity'] -= $sellQty;

                        // Log the used quantity from this batch
                        $logEntry['details'][] = [
                            'used_qty' => $sellQty,
                            'remaining_qty' => $batch['quantity'],
                            'unit_price' => $batch['unit_price'],
                        ];

                        // If there is remaining quantity in the batch, put it back
                        if ($batch['quantity'] > 0) {
                            array_unshift($inventoryStack, $batch);
                        }

                        // Update the total quantity and value
                        $totalQuantity -= $sellQty;
                        $totalValue -= $sellQty * $batch['unit_price'];
                        $sellQty = 0; // All sale quantity processed
                    } else {
                        // If the sale quantity is larger than the batch quantity
                        $costOfGoodsSold += $batch['quantity'] * $batch['unit_price'];
                        $totalUsedQty += $batch['quantity']; // Track total used quantity
                        $sellQty -= $batch['quantity'];

                        // Log the used quantity from this batch
                        $logEntry['details'][] = [
                            'used_qty' => $batch['quantity'],
                            'remaining_qty' => 0, // Batch is fully used
                            'unit_price' => $batch['unit_price'],
                        ];

                        // Update the total quantity and value
                        $totalQuantity -= $batch['quantity'];
                        $totalValue -= $batch['quantity'] * $batch['unit_price'];
                    }
                }

                // Calculate profit/loss for this transaction
                $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
                $profitLoss = $totalSaleValue - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;

                // Calculate new average cost using the new formula
                $averageCost = $totalUsedQty > 0 ?
                    (($costOfGoodsSold + ($transaction->unit_price * $totalUsedQty)) / $totalUsedQty)
                    : 0;
                // dump($averageCost);

                // Log the sale details
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
                    'average_cost' => $averageCost, // New average cost
                    'details' => $logEntry['details'],
                    'inventory_stack' => $inventoryStack,
                    'balance_unit_price' => (abs($totalQuantity) > 0) ? $totalValue / $totalQuantity : 0,
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                ];
            }
        }

        // Return the calculated average data and transaction logs
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




    public function showFifoReport($id, $item_id)
    {

        $fifoData = $this->calculateFIFO($id, $item_id);
        //  dd($fifoData);
        return view('inventory_valuation.fifo', $fifoData);
    }

    public function showLifoReport($id, $item_id)
    {
        $lifoData = $this->calculateLIFO($id, $item_id);
        return view('inventory_valuation.lifo', $lifoData);
    }


    public function filterData(Request $request)
    {
        // Get input from the request
        $filterType = $request->input('filterType');
        $toDate = $request->input('to_date');
        $fromDate = $request->input('from_date');
        $categoryName = $request->input('category'); // Get the selected category name
        // Start the query on the InventoryTransaction model
        $query = InventoryTransaction::query();

        // Apply filter type if provided
        if ($filterType) {
            $query->where('transaction_type', $filterType);
        }

        // Filter by date range if provided
        if ($toDate) {
            $query->where('transaction_date', '<=', $toDate);
        }
        if ($fromDate) {
            $query->where('transaction_date', '>=', $fromDate);
        }

        // Filter by category if provided
        if ($categoryName) {
            $query = InventoryTransaction::where('item_name', $categoryName)->get();
        }

        // Fetch the filtered inventory transactions
        $inventory_transaction =  $query;
        // dd($inventory_transaction);

        // Uncomment the dd statement to debug
        // dd($inventory_transaction);

        // Calculate LIFO, FIFO, and Average data
        $lifoData = $this->calculateLIFO();
        $fifoData = $this->calculateFIFO();
        $avgData = $this->calculateAverage();

        // Return the data as a JSON response
        return response()->json([
            'inventory_transaction' => $inventory_transaction, // Change this to 'data' to match your frontend code
            'lifo_transaction' => $lifoData['transaction_logs'],
            'fifo_transaction' => $fifoData['transaction_logs'],
            'avgData' => $avgData,
        ]);
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

        $avgData = $this->calculateAverage($filterFromdate, $filterTodate);


        $data = [];

        $tempData = [
            'last_transaction_date' =>  $lifoData['last_transaction_date'] ?? 'N/A',
            'item_name' => $lifoData['item_name'] ?? 'N/A',
            'final_balance_qty' => $lifoData['final_balance_qty'] ?? 'N/A',
            'quantity' => $lifoData['final_balance_value'] ?? 'N/A',
            'item_name' => $fifoData['final_balance_value'] ?? 'N/A',
            'company_name' => $fifoData['final_balance_value'] ?? 'N/A',
            'company_name' => $lifoData['manual_match'] ?? 'N/A',
            'company_name' => $lifoData['netwise'] ?? 'N/A',
        ];
        $data[] = $tempData;




        return response()->json($data);
    }
}
