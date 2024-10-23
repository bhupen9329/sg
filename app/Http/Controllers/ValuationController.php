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
        return view('inventory_valuation.index', compact('inventory', 'companies'));
    }


    public function calculateLIFO($id = null)
    {
        if ($id) {
            $transaction_data = InventoryTransaction::where('id', $id)->select('transaction_date')->first();
            // $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

            // Get all transactions up to and including the specific transaction_date
            $transactions = InventoryTransaction::where('transaction_date', '<=', $transaction_data->transaction_date)
                ->orderBy('transaction_date', 'asc')
                ->get();
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

                    // Update inventory totals
                    $totalQuantity += $transaction->quantity;
                    $totalValue += $transaction->quantity * $transaction->unit_price;
                } else {
                    $lastPurchaseTotal = 0;
                    while ($poQty > 0 && !empty($inventoryStack)) {
                        // Get the last purchase (LIFO: Last In First Out)
                        $lastPurchase = array_pop($inventoryStack);
                        $lasttotalValue = ($lastPurchase['quantity'] * $lastPurchase['unit_price']);
                        $lastPurchaseTotal +=  $lasttotalValue;


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
                    'balance_unit_price' => $totalValue / $totalQuantity, // Avoid division by zero
                    'cost_of_goods_sold' => $costOfGoodsPurchased,
                    'profit_loss' => 0,
                    'status' => $totalQuantity < 0 ? 'Short' : 'Long',
                    'log_amount' => $transaction->quantity * $transaction->unit_price,
                    'inventory_stack' => $inventoryStack,
                    'details' => $logEntry['details'],
                    'lastPurchaseTotal' =>  $lastPurchaseTotal,
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
        $finalPrice = ($lastTransactionStatus === 'Long') ? $totalValue / $totalQuantity : $totalValue / $totalQuantity;

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


    public function showLifoReport($id)
    {
        $lifoData = $this->calculateLIFO($id);


        return view('inventory_valuation.lifo', $lifoData);
    }

    public function calculateFIFO()
    {
        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

        $firstTransaction = $transactions->first();
        $firstTransactionDate = $firstTransaction ? $firstTransaction->transaction_date : null;

        $inventoryQueue = [];
        $transactionLogs = [];
        $totalQuantity = 0; // Total stock balance quantity
        $totalValue = 0; // Total stock balance value
        $totalProfitLoss = 0;
        $status = 'Long'; // Initial status is Long (surplus)

        foreach ($transactions as $transaction) {
            $item_name = $transaction->item_name;

            if (strtolower($transaction->transaction_type) === 'purchase') {
                // Purchase transaction
                $purchaseQty = $transaction->quantity;
                $unitPrice = $transaction->unit_price;

                // If status is Short (deficit), offset shortfall with this purchase
                if ($status === 'Short') {
                    if ($purchaseQty >= abs($totalQuantity)) {
                        // Offset the shortfall completely
                        $purchaseQty -= abs($totalQuantity);
                        $totalQuantity = 0; // Reset deficit to zero
                        $status = 'Long'; // Now inventory is balanced or surplus
                    } else {
                        $totalQuantity += $purchaseQty; // Reduce deficit but still short
                        $purchaseQty = 0; // No new stock added to inventory since it offsets shortfall
                    }
                }

                // Add remaining quantity to inventory queue and balance after offsetting shortfall
                if ($purchaseQty > 0) {
                    $inventoryQueue[] = [
                        'quantity' => $purchaseQty,
                        'unit_price' => $unitPrice,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                    // Correctly update the total quantity and value
                    $totalQuantity += $purchaseQty;
                    $totalValue += $purchaseQty * $unitPrice;

                    // Log the purchase transaction
                    $transactionLogs[] = [
                        'transaction_type' => 'Purchase',
                        'quantity' => $purchaseQty,
                        'unit_price' => $unitPrice,
                        'transaction_date' => $transaction->transaction_date,
                        'balance_qty' => $totalQuantity,
                        'balance_value' => $totalValue,
                        'balance_unit_price' => $totalValue / $totalQuantity ??  0, // Avoid division by zero
                        'cost_of_goods_sold' => 0,
                        'profit_loss' => 0,
                        'status' => $status,
                        'inventory_queue' => $inventoryQueue,
                        'details' => [
                            [
                                'used_qty' => $purchaseQty,
                                'unit_price' => $unitPrice,
                                'remaining_qty' => 0, // For purchase, remaining qty is zero as it's fully added
                                'remaining_value' => 0,
                            ]
                        ],
                    ];
                }
            } elseif (strtolower($transaction->transaction_type) === 'sell') {
                // Sell transaction
                $sellQty = abs($transaction->quantity);
                $costOfGoodsSold = 0;
                $logEntry = [
                    'transaction_type' => 'Sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $transaction->unit_price,
                    'details' => [],
                    'total_amount' => 0,
                ];

                // Correctly reduce the stock balance by selling using FIFO
                while ($sellQty > 0 && !empty($inventoryQueue)) {
                    $firstPurchase = array_shift($inventoryQueue);

                    if ($firstPurchase['quantity'] >= $sellQty) {
                        // If the purchase quantity is enough to cover the sell
                        $remainingQty = $firstPurchase['quantity'] - $sellQty;

                        // If there is any remaining quantity, push it back to the inventory
                        if ($remainingQty > 0) {
                            $inventoryQueue[] = [
                                'quantity' => $remainingQty,
                                'unit_price' => $firstPurchase['unit_price'],
                                'transaction_date' => $firstPurchase['transaction_date'],
                            ];
                        }

                        // Calculate cost and reduce sell quantity
                        $costOfGoodsSold += $sellQty * $firstPurchase['unit_price'];
                        $logEntry['total_amount'] += $sellQty * $transaction->unit_price;

                        $logEntry['details'][] = [
                            'used_qty' => $sellQty,
                            'unit_price' => $firstPurchase['unit_price'],
                            'amount' => $sellQty * $firstPurchase['unit_price'],
                            'remaining_qty' => $remainingQty,
                            'remaining_value' => $remainingQty * $firstPurchase['unit_price'],
                        ];

                        $sellQty = 0; // Fully satisfied this sell order
                    } else {
                        // If the first purchase cannot cover the sell quantity, use it fully
                        $costOfGoodsSold += $firstPurchase['quantity'] * $firstPurchase['unit_price'];
                        $logEntry['total_amount'] += $firstPurchase['quantity'] * $transaction->unit_price;

                        $logEntry['details'][] = [
                            'used_qty' => $firstPurchase['quantity'],
                            'unit_price' => $firstPurchase['unit_price'],
                            'amount' => $firstPurchase['quantity'] * $firstPurchase['unit_price'],
                            'remaining_qty' => 0,
                            'remaining_value' => 0,
                        ];
                        $sellQty -= $firstPurchase['quantity'];
                    }
                }

                if ($sellQty > 0) {
                    // If still remaining sell quantity, inventory goes Short
                    $status = 'Short';
                    $totalQuantity -= $sellQty; // Reflect shortfall in total quantity

                    $logEntry['details'][] = [
                        'used_qty' => 0,
                        'unit_price' => 0,
                        'remaining_qty' => -$sellQty,
                        'short_qty' => -$sellQty,
                        'remaining_value' => 0,
                    ];
                }

                // Calculate total sale and profit/loss
                $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
                $profitLoss = $totalSaleValue - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;

                // Log the sell transaction
                $transactionLogs[] = [
                    'transaction_type' => 'Sell',
                    'quantity' => abs($transaction->quantity),
                    'selling_price' => $transaction->unit_price,
                    'actual_sales_value' => $totalSaleValue,
                    'unit_sell_price' => $totalSaleValue / abs($transaction->quantity),
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'balance_unit_price' => $totalValue / $totalQuantity ??  0, // Avoid division by zero
                    'unit_cogs_price' => $costOfGoodsSold / abs($transaction->quantity),
                    'cost_of_goods_sold' => $costOfGoodsSold,
                    'profit_loss' => $profitLoss,
                    'total_profit_loss' => $totalProfitLoss,
                    'status' => $status,
                    'inventory_queue' => $inventoryQueue,
                    'details' => $logEntry['details'], // Log the details
                ];
            }
        }

        // Determine final price based on the last transaction status
        $finalPrice = !empty($inventoryQueue) ? $inventoryQueue[0]['unit_price'] : 'N/A';

        return [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue,
            'final_profit_loss' => $totalProfitLoss,
            'last_transaction_status' => $status,
            'final_price' => $finalPrice,
            'first_transaction_date' => $firstTransactionDate,
        ];
    }





    // Sample logTransaction method to log the transactions
    protected function logTransaction($type, $transaction, $totalQty, $totalValue, $cogs, $profitLoss, $inventoryStack)
    {
        return [
            'type' => $type,
            'transaction_id' => $transaction->id,
            'transaction_date' => $transaction->transaction_date,
            'quantity' => $transaction->quantity,
            'unit_price' => $transaction->unit_price,
            'total_quantity' => $totalQty,
            'total_value' => $totalValue,
            'cost_of_goods_sold' => $cogs,
            'profit_loss' => $profitLoss,
            'inventory_queue' => $inventoryStack, // Ensure the stack is passed correctly
        ];
    }








    public function getPositionReport()
    {
        $lifoData = $this->calculateLIFO();
        $fifoData = $this->calculateFIFO();
        $avgData = $this->calculateAverage();
        $lifo_transaction = $lifoData['transaction_logs'];
        $fifo_transaction = $fifoData['transaction_logs'];
        $inventory_transaction = InventoryTransaction::all();


        return view('inventory_valuation.position_report', compact('lifoData', 'fifoData', 'avgData', 'inventory_transaction', 'lifo_transaction', 'fifo_transaction'));
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
