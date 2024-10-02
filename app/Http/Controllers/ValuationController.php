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
                
                $inventoryStack[] = [
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                ];
    
                
                $totalQuantity += $transaction->quantity;
                $totalValue += $transaction->quantity * $transaction->unit_price;
    
               
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
    
               
                while ($sellQty > 0 && !empty($inventoryStack)) {
                    $lastPurchase = array_pop($inventoryStack); 
    
                    if ($lastPurchase['quantity'] >= $sellQty) {
                        
                        $costOfGoodsSold += $sellQty * $lastPurchase['unit_price'];
                        $totalQuantity -= $sellQty;
                        $totalValue -= $sellQty * $lastPurchase['unit_price'];
    
                     
                        $remainingQty = $lastPurchase['quantity'] - $sellQty;
                        $remainingValue = $remainingQty * $lastPurchase['unit_price'];
    
                      
                        if ($remainingQty > 0) {
                            $inventoryStack[] = [
                                'quantity' => $remainingQty,
                                'unit_price' => $lastPurchase['unit_price'],
                                'transaction_date' => $lastPurchase['transaction_date'],
                            ];
                        }
    
                        $logEntry['details'][] = [
                            'used_qty' => $sellQty,
                            'unit_price' => $lastPurchase['unit_price'],
                            'remaining_qty' => $remainingQty,
                            'remaining_value' => $remainingValue,
                        ];
                        $sellQty = 0; 
    
                    } else {
                       
                        $costOfGoodsSold += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                        $sellQty -= $lastPurchase['quantity'];
                        $totalQuantity -= $lastPurchase['quantity'];
                        $totalValue -= $lastPurchase['quantity'] * $lastPurchase['unit_price'];
    
                       
                        $logEntry['details'][] = [
                            'used_qty' => $lastPurchase['quantity'],
                            'unit_price' => $lastPurchase['unit_price'],
                            'remaining_qty' => 0,
                            'remaining_value' => 0,
                        ];
                    }
                }
    
                
                $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
                $profitLoss = $totalSaleValue - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;
    
            
                $breakEvenPrice = $costOfGoodsSold / abs($transaction->quantity);
    
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
                    'status' => $totalQuantity >= 0 ? 'Long' : 'Short', 
                    'details' => $logEntry['details'], 
                    'break_even_price' => $breakEvenPrice, 
                ];
                // dd($transactionLogs);
            }
        }
    
        return view('inventory_valuation.lifo', [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue, 
            'final_profit_loss' => $totalProfitLoss,
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
