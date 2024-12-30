<?php

namespace App\Http\Controllers;

use App\Models\Ageing;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\StockItem;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\WareHouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{

 

    public function index()
    {
        $adjustment_data = StockAdjustment::join('warehouse', 'stock_adjustments.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('users', 'stock_adjustments.user_id', '=', 'users.id')
            ->leftJoin('stock_adjustment_items', 'stock_adjustments.id', '=', 'stock_adjustment_items.adjustment_id')
            ->select(
                'stock_adjustments.id as sadj_id',
                'stock_adjustments.adjustment_number',
                'stock_adjustments.date',
                'warehouse.warehouse_title',
                DB::raw('SUM(stock_adjustment_items.piece) as piece'),
                'users.name'
            )
            ->groupBy(
                'stock_adjustments.id',
                'stock_adjustments.adjustment_number',
                'stock_adjustments.date',
                'warehouse.warehouse_title',
                'users.name'
            )
            ->orderBy('stock_adjustments.id', 'desc')
            ->get();

        $warehouse = WareHouseModel::get();
        // dd($adjustment_data);
        return view('stock_adjustment.index', compact('adjustment_data', 'warehouse',));
    }


    public function create(Request $request)
    {

        $year = date('Y');
        $warehouse = WareHouseModel::where('id', $request->warehouse_id)->first();
        $item_category = Category::get();
        $latestAdjustment = StockAdjustment::join('stock_adjustment_items', 'stock_adjustment_items.adjustment_id', '=', 'stock_adjustments.id')->orderBy('adjustment_number', 'desc')->first();

        // dd($latestAdjustment);

        if ($latestAdjustment) {
            $max_serial_number = $latestAdjustment->adjustment_number;
            $last_serial_number = substr($max_serial_number, -4);
            $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_serial_number = str_pad(1, 4, '0', STR_PAD_LEFT);
        }

        $stock_adjustment_number = 'AD' .  $year . $next_serial_number;

        $user_id = Auth::user()->id;
        $data = [
            'stock_adjustment_number' => $stock_adjustment_number,
            'category' => $item_category,
            'warehouse' => $warehouse,
            'user_id' => $user_id,
        ];
        // dd($data);
        return view('stock_adjustment.create')->with($data);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $stockAdjustment = new StockAdjustment();
        $stockAdjustment->date = $request->date;
        $stockAdjustment->adjustment_number = $request->adjustment_number;
        $stockAdjustment->warehouse_id = $request->warehouse_id;
        $stockAdjustment->user_id = $request->user_id;
        $stockAdjustment->remark = $request->remark;
        $stockAdjustment->save();

        $id = $stockAdjustment->id;

        for ($i = 0; $i < count($request->item_category); $i++) {
            $warehouse_id = $request->warehouse_id;
            $category_id = $request->item_category[$i];
            $sub_category_id = $request->item_sub_category[$i];
            $length = $request->length[$i];
            $type = trim($request->type[$i]); // Trim to remove whitespace

            // Check if type is selected and valid
            if (!in_array($type, ['Addition', 'Subtraction'])) {
                // Handle invalid type here
                continue; // Skip to the next iteration
            }

            // Determine adjustment based on type
            $adjustmentPiece = ($type == 'Addition') ? $request->piece[$i] : $request->piece[$i];
            $adjustmentWeight = ($type == 'Addition') ? $request->weight[$i] : $request->weight[$i];


            // dd($adjustmentPiece, $adjustmentWeight);

            // Create a new stock adjustment item
            $newStockAdjustmentItem = new StockAdjustmentItem();
            $newStockAdjustmentItem->category_id = $category_id;
            $newStockAdjustmentItem->sub_category_id = $sub_category_id;
            $newStockAdjustmentItem->length = $length;
            $newStockAdjustmentItem->piece = $adjustmentPiece;
            $newStockAdjustmentItem->weight = $adjustmentWeight;
            $newStockAdjustmentItem->type = $type;
            $newStockAdjustmentItem->warehouse_id = $warehouse_id;
            $newStockAdjustmentItem->adjustment_id = $id;
            $newStockAdjustmentItem->save();

            // Update the stock items based on the adjustment type
            $stockItem = StockItem::where('warehouse_id', $warehouse_id)
                ->where('category_id', $category_id)
                ->where('sub_category_id', $sub_category_id)
                ->where('length', $length)
                ->first();

            if ($stockItem) {
                if ($type == 'Addition') {
                    $stockItem->piece += $request->piece[$i];
                    $stockItem->weight += $request->weight[$i];


                    // .................................................Stock Transaction................................................................... 
                    $data = [
                        'category_id' => $request->item_category[$i],
                        'ref_id' => $request->adjustment_number,
                        'subcategory_id' => $request->item_sub_category[$i],
                        'warehouse_id' => $request->warehouse_id,
                        'length' => $request->length[$i],
                        'pcs' => $request->piece[$i],
                        'type' => 'stock adjustment',
                        'operation' => 'addition(+)',
                        'user_id' => $user->id,
                    ];

  

                    Transaction::create($data);

                    // ........................................................................................................................................

                    // .................................................Ageing................................................................................ 
                    $data_ageing = [
                        'category_id' => $request->item_category[$i],
                        'subcategory_id' => $request->item_sub_category[$i],
                        'length' => $request->length[$i],
                        'qty' =>  $request->piece[$i],
                        'balance' =>  $request->piece[$i],
                        'warehouse_id' => $request->warehouse_id,
                    ];
                    Ageing::create($data_ageing);

                    // ........................................................................................................................................

                } else if ($type == 'Subtraction') {
                    $stockItem->piece -= $request->piece[$i];
                    $stockItem->weight -= $request->weight[$i];


                    // ........................................................................Ageing.................................................................... 

                    $ageingItems = Ageing::where('warehouse_id', $request->warehouse_id)
                        ->where('category_id', $request->item_category[$i])
                        ->where('subcategory_id', $request->item_sub_category[$i])
                        ->where('length', $request->length[$i])
                        ->orderBy('id', 'asc')
                        ->get();

                    $data_piece = $request->piece[$i];

                    foreach ($ageingItems as $ageingItem) {
                        if ($data_piece <= 0) {
                            break; // Stop processing if $data_piece is 0 or less
                        }

                        if ($ageingItem->balance == 0) {
                            continue; // Skip to the next $ageingItem if balance is 0
                        }

                        $balance_pcs = ($ageingItem->balance - $data_piece);

                        if ($balance_pcs < 0) {
                            // If deduction exceeds balance, set balance to 0 and adjust $data_piece
                            $ageingItem->balance = 0;
                            $data_piece = abs($balance_pcs);
                        } else {
                            // Otherwise, deduct $data_piece from balance and set $data_piece to 0
                            $ageingItem->balance = $balance_pcs;
                            $data_piece = 0;
                        }

                        $ageingItem->save(); // Save the updated $ageingItem
                    }

                    // ...........................................................................................................................................

                    $data = [
                        'category_id' => $request->item_category[$i],
                        'ref_id' => $request->adjustment_number,
                        'subcategory_id' => $request->item_sub_category[$i],
                        'warehouse_id' => $request->warehouse_id,
                        'length' => $request->length[$i],
                        'pcs' => $request->piece[$i],
                        'type' => 'stock adjustment',
                        'operation' => 'subtraction(-)',
                        'user_id' => $user->id,
                    ];
                    Transaction::create($data);
                }

                $stockItem->save();
            } else if ($type == 'Addition') {
                // Create a new stock item if it doesn't exist (only for additions)
                $newStockItem = new StockItem();
                $newStockItem->warehouse_id = $warehouse_id;
                $newStockItem->category_id = $category_id;
                $newStockItem->sub_category_id = $sub_category_id;
                $newStockItem->length = $length;
                $newStockItem->piece = $request->piece[$i];
                $newStockItem->weight = $request->weight[$i];
                $newStockItem->save();

                // .................................................Stock Transaction................................................................................ 


                $data = [
                    'category_id' => $request->item_category[$i],
                    'ref_id' => $request->adjustment_number,
                    'subcategory_id' => $request->item_sub_category[$i],
                    'warehouse_id' => $request->warehouse_id,
                    'length' => $request->length[$i],
                    'pcs' => $request->piece[$i],
                    'type' => 'stock adjustment',
                    'operation' => 'addition(+)',
                    'user_id' => $user->id,
                ];
                Transaction::create($data);
                // ........................................................................................................................................



                // .................................................Ageing................................................................................ 
                $data_ageing = [
                    'category_id' => $request->item_category[$i],
                    'subcategory_id' => $request->item_sub_category[$i],
                    'length' => $request->length[$i],
                    'qty' =>  $request->piece[$i],
                    'balance' =>  $request->piece[$i],
                    'warehouse_id' => $request->warehouse_id,
                ];
                Ageing::create($data_ageing);

                // ........................................................................................................................................
            }
        }
        return redirect()->route('adjustment.index')->with('success', 'StockAdjustment created Successfully');
    }


    public function delete($id)
    {
        $user = Auth::user();
        $std_data = StockAdjustmentItem::where('adjustment_id', $id)->get();
        $adjustment = StockAdjustment::where('id', $id)->first();

        // dd($std_data);
        foreach ($std_data as $data) {
            $warehouse_id = $data->warehouse_id;
            $category_id = $data->category_id;
            $sub_category_id = $data->sub_category_id;
            $length = $data->length;
            // dd($warehouse_id, $category_id, $sub_category_id, $length);
            $stockItem = StockItem::where('warehouse_id', $warehouse_id)
                ->where('category_id', $category_id)
                ->where('sub_category_id', $sub_category_id)
                ->where('length', $length)
                ->first();
            // dd($stockItem);
            if ($data->type == 'Subtraction') {
                $stockItem->piece += $data->piece;
                $stockItem->weight += $data->weight;


                // .................................................Stock Transaction................................................................................ 
                $data_transaction = [
                    'category_id' => $category_id,
                    'ref_id' => $adjustment->adjustment_number,
                    'subcategory_id' => $sub_category_id,
                    'warehouse_id' =>  $warehouse_id,
                    'pcs' => $data->piece,
                    'length' => $length,
                    'type' => 'stock adjustment',
                    'operation' => 'addition(+)',
                    'user_id' => $user->id,
                ];
                Transaction::create($data_transaction);

                // ........................................................................................................................................

                // .................................................Ageing................................................................................ 
                $data_ageing = [
                    'category_id' => $category_id,
                    'subcategory_id' => $sub_category_id,
                    'length' => $length,
                    'qty' =>  $data->piece,
                    'balance' =>  $data->piece,
                    'warehouse_id' => $warehouse_id,
                ];
                Ageing::create($data_ageing);

                // ........................................................................................................................................


            } else {

                if (($stockItem->piece < $data->piece) &&   $stockItem->weight < $data->weight) {
                    return redirect()->route('adjustment.index')->with('msg', 'Your Delete Item is not in Stock');
                }
                $stockItem->piece -= $data->piece;
                $stockItem->weight -= $data->weight;

                // ........................................................................Ageing.................................................................... 

                $ageingItems = Ageing::where('warehouse_id', $warehouse_id)
                    ->where('category_id', $category_id)
                    ->where('subcategory_id', $sub_category_id)
                    ->where('length', $length)
                    ->orderBy('id', 'asc')
                    ->get();

                $data_piece = $data->piece;

                foreach ($ageingItems as $ageingItem) {
                    if ($data_piece <= 0) {
                        break; // Stop processing if $data_piece is 0 or less
                    }

                    if ($ageingItem->balance == 0) {
                        continue; // Skip to the next $ageingItem if balance is 0
                    }

                    $balance_pcs = ($ageingItem->balance - $data_piece);

                    if ($balance_pcs < 0) {
                        // If deduction exceeds balance, set balance to 0 and adjust $data_piece
                        $ageingItem->balance = 0;
                        $data_piece = abs($balance_pcs);
                    } else {
                        // Otherwise, deduct $data_piece from balance and set $data_piece to 0
                        $ageingItem->balance = $balance_pcs;
                        $data_piece = 0;
                    }

                    $ageingItem->save(); // Save the updated $ageingItem
                }

                // ...........................................................................................................................................

                $data = [
                    'category_id' => $category_id,
                    'ref_id' => $adjustment->adjustment_number,
                    'subcategory_id' => $sub_category_id,
                    'warehouse_id' =>  $warehouse_id,
                    'pcs' => $data->piece,
                    'length' => $length,
                    'type' => 'stock adjustment',
                    'operation' => 'subtraction(-)',
                    'user_id' => $user->id,
                ];
                Transaction::create($data);
            }
            $stockItem->save();
        }

        StockAdjustment::where('id', $id)->delete();
        StockAdjustmentItem::where('adjustment_id', $id)->delete();
        return redirect()->route('adjustment.index')->with('delete', 'Stock Adjustment Delete Successfully');
    }


    public function checkQuantity(Request $request)
    {
        // Validate the request data
        $request->validate([
            'warehouse_id' => 'required|integer',
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'length' => 'required|numeric',
        ]);

        // Fetch the piece value from the database
        $piece = StockAdjustmentItem::where([
            'warehouse_id' => $request->warehouse_id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'length' => $request->length,
        ])->value('piece');

        // Return the piece as JSON response
        return response()->json(['piece' => $piece]);
    }


    public function Categorydetails(Request $request)
    {
        $stock_adj = StockAdjustmentItem::where('adjustment_id', $request->id)
            ->join('stock_adjustments', 'stock_adjustment_items.adjustment_id', '=', 'stock_adjustments.id')
            ->join('categories', 'stock_adjustment_items.category_id', '=', 'categories.id')
            ->join('subcategories', 'stock_adjustment_items.sub_category_id', '=', 'subcategories.id')
            ->get();

        // Return the provider details as a JSON response
        return response()->json($stock_adj);
    }
}
