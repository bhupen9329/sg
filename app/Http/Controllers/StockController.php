<?php

namespace App\Http\Controllers;

use App\Models\Ageing;
use App\Models\Category;
use App\Models\Inward;
use App\Models\SoItem;
use App\Models\StockItem;
use App\Models\Transaction;
use App\Models\WareHouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:Stocks-index', ['only' => ['index']]);
        $this->middleware('permission:Stocks-create', ['only' => ['create','store']]);
    }


  
    public function index()
    {

        $stock_item_data = StockItem::join('categories', 'stock_items.category_id', '=', 'categories.id')
            ->join('subcategories', 'stock_items.sub_category_id', '=', 'subcategories.id')
            ->join('companies', 'stock_items.supplier_id', '=', 'companies.id')
            ->orderBy('stock_items.id', 'desc')
            ->select(
                'categories.id as category_id',
                'categories.name',
                'companies.id as company_id',
                'companies.company_name',
                'companies.virtual_store',
                'subcategories.id as sub_category_id',
                'subcategories.sub_category',
                'stock_items.id as stock_items_id',
                'stock_items.supplier_id as virtual_supplier_id',
                'stock_items.weight as w_weight',
            )
            ->groupBy(
                'categories.id',
                'categories.name',
                'companies.id',
                'companies.company_name',
                'companies.virtual_store',
                'subcategories.id',
                'subcategories.sub_category',
                'stock_items.supplier_id',
                'stock_items.id',
                'stock_items.weight',
            )
            ->orderBy('categories.id', 'desc')
            ->get();

        // dd($stock_item_data);

        $warehouse = WareHouseModel::get();
        // dd($stock_item_data);
        return view('stock.index', compact('stock_item_data', 'warehouse', ));
    }

    public function create(Request $request)
    {
        // dd($request);
        $warehouse = WareHouseModel::where('id', $request->warehouse_id)->first();
        // dd($warehouse);
        $item_category = Category::get();
        $data = [
            'category' => $item_category,
            'warehouse' => $warehouse,
        ];
        // dd($data);
        return view('stock.create')->with($data);
    }

    public function store(Request $request)
    {
        // dd($request);
        $stock_data = StockItem::get();
        $user = Auth::user();
        for ($i = 0; $i < count($request->item_category); $i++) {
            $warehouse_id = $request->warehouse_id;
            $category_id = $request->item_category[$i];
            $sub_category_id = $request->item_sub_category[$i];
            $length = $request->length[$i];

            // Check if there's an existing stock item that matches the criteria
            $existingStockItem = $stock_data->firstWhere(function ($s_data) use ($warehouse_id, $category_id, $sub_category_id, $length) {
                return $s_data->warehouse_id == $warehouse_id && $s_data->category_id == $category_id
                    && $s_data->sub_category_id == $sub_category_id && $s_data->length == $length;
            });

            if ($existingStockItem) {
                // Update the existing stock item
                $existingStockItem->length = $request->length[$i];
                $existingStockItem->piece += $request->piece[$i];
                $existingStockItem->weight += $request->weight[$i];
                $existingStockItem->save();
            } else {
                // Create a new stock item
                $inwardItem = new StockItem();
                $inwardItem->category_id = $request->item_category[$i];
                $inwardItem->sub_category_id = $request->item_sub_category[$i];
                $inwardItem->length = $request->length[$i];
                $inwardItem->piece = $request->piece[$i];
                $inwardItem->weight = $request->weight[$i];
                $inwardItem->warehouse_id = $request->warehouse_id;
                $inwardItem->save();
            }

            $data = [
                'category_id' => $request->item_category[$i],
                'ref_id' => 'N/A',
                'subcategory_id' => $request->item_sub_category[$i],
                'warehouse_id' => $request->warehouse_id,
                'length' => $request->length[$i],
                'pcs' => $request->piece[$i],
                'type' => 'stock',
                'operation' => 'addition(+)',
                'user_id' => $user->id,
            ];
            Transaction::create($data);

            $data_ageing = [
                'category_id' => $request->item_category[$i],
                'subcategory_id' => $request->item_sub_category[$i],
                'length' => $request->length[$i],
                'qty' => $request->piece[$i],
                'balance' => $request->piece[$i],
                'warehouse_id' => $request->warehouse_id,
            ];
            Ageing::create($data_ageing);
        }

        return redirect()->route('stock.index')->with('success', 'Stock created Successfully.');

        // dd($po_data);
    }
    public function get_current_quantity_list(Request $request)
    {
        $item_id = $request->item_id;
        $sub_category_id = $request->item_sub_category;
        $length = $request->length;
        $warehouse = $request->warehouse_id;

        // $data = [
        //     'item_id' => $item_id,
        //     'sub_category_id' => $sub_category_id,  
        //     'length' => $length,
        //     'warehouse' => $warehouse,
        // ];
        // dd($data );

        $data = StockItem::where('category_id', $item_id)
            ->where('sub_category_id', $sub_category_id)
            ->where('length', $length)
            ->where('warehouse_id', $warehouse)
            ->first();
        // dd($item_id, $sub_category_id, $length, $warehouse);

        return response([
            'data' => $data
        ]);
    }

    public function get_reserved_details(Request $request)
    {
        $stock_data = StockItem::where('id', $request->stock_items_id)->get();

        foreach ($stock_data as $stock) {
            $category_id = $stock->category_id;
            $sub_category_id = $stock->sub_category_id;
            $length = $stock->length;
        }
        $so_items = SoItem::join('sales_orders', 'so_items.sale_id', '=', 'sales_orders.id')
            ->join('companies', 'sales_orders.company_id', '=', 'companies.id')
            ->where('so_items.warehouse_id', $request->warehouse_id)
            ->where('so_items.item_category', $category_id)
            ->where('so_items.item_subcategory', $sub_category_id)
            ->where('so_items.length', $length)
            ->get();


        return response($so_items);
    }
    public function value_for_virtual_store(Request $request)
    {
        $supplier_id = $request->supplier_id;
        $category_id = $request->category_id;
        $sub_category_id = $request->sub_category_id;
        // dd($supplier_id, $category_id, $sub_category_id);
        $inward_data = Inward::join('inwards', 'stock_items.supplier_id', '=', 'inwards.supplier_id')
            ->join('inward_items', 'inwards.id', '=', 'inward_items.inward_id')
            ->where('inwards.supplier_id', $supplier_id)
            ->select(
                'inwards.inward_number',
                'outwards.outward_number',
            )
            ->get();



        return response();
    }


    public function incrementAge()
    {
        // Update the age for all users where the balance is not equal to 0
        Ageing::where('balance', '!=', 0)->increment('age');

        // Return a JSON response indicating success
        return response()->json(['message' => 'Ages updated successfully.']);
    }
}
