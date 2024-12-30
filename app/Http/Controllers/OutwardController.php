<?php

namespace App\Http\Controllers;

use App\Livewire\Sales;
use App\Models\Ageing;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Outward;
use App\Models\OutwardItem;
use App\Models\SalesOrder;
use App\Models\SoItem;
use App\Models\StockItem;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\WareHouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OutwardController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('permission:Outward-index', ['only' => ['index']]);
    //     $this->middleware('permission:Outward-create', ['only' => ['create', 'create_so', 'store', 'store_so']]);
    //     $this->middleware('permission:Outward-view', ['only' => ['edit']]);
    //     $this->middleware('permission:Outward-edit', ['only' => ['update']]);
    //     $this->middleware('permission:Outward-approve', ['only' => ['approve']]);
    //     $this->middleware('permission:Outward-delete', ['only' => ['delete', '']]);
    // }
    public function index()
    {
        $outward_data = Outward::join('companies', 'outwards.company_id', '=', 'companies.id')
            ->leftJoin('sales_orders', 'outwards.supplier_id', '=', 'sales_orders.id')
            ->select('companies.*', 'sales_orders.*', 'outwards.*', 'outwards.id as ot_id', )
            ->orderBy('outwards.id', 'desc')
            ->get();

        $warehouse = WareHouseModel::get();
        $companies = Company::get();
        // $sales_order = SalesOrder::join('quotations', 'quotations.id', '=', 'sales_orders.qt_id' )->where('quotations.loading_point', '!=', 'direct')->get();
        // dd( $sales_order);

        $sales_order = SalesOrder::where('status', 'pending')
            ->orWhere('status', 'partial pending')
            ->get();

        // $exceed = Outward::join('outward_items', 'outwards.id', '=', 'outward_items.outward_id')
        //     ->where('outward_items.exceed_pcs', '!=', 0)
        //     ->get();

        $data = [
            'outward_data' => $outward_data,
            'warehouse' => $warehouse,
            'companies' => $companies,
            'sales_order' => $sales_order,
        ];
        // dd($data);

        return view('outward.index')->with($data);
    }

    public function create(Request $request)
    {
        // dd($request);

        // $max_serial_number = Outward::where('company_id', $request->company_id)->max('outward_number');
        // $last_serial_number = substr($max_serial_number, -4);
        // $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        // $outward_id = 'JLOT' . $next_serial_number;
        $year = date('Y');
        $max_serial_number = Outward::max('outward_number');
        $last_serial_number = substr($max_serial_number, -4);
        $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        $outward_id = 'OT' . $year . $next_serial_number;

        $company = Company::where('id', $request->company_id)->first();
        $supplier_data = Company::where('id', $request->supplier_id)->first();
        $sale_order = SalesOrder::where('id', $request->so_id)->first();
        $item_category = Category::get();

        $company_setting = CompanySetting::first();
        $data = [
            'outward_id' => $outward_id,
            'outward_type' => $request->selected_type,
            'company' => $company,
            'company_setting' => $company_setting,
            'supplier_data' => $supplier_data,
            'sale_order' => $sale_order,
            'so_number' => $request->so_id,
            'category' => $item_category,


        ];
        // dd($data);
        return view('outward.create')->with($data);
    }

    public function create_so(Request $request)
    {
        $check_avilability = Outward::where('so_id', $request->so_id)->where('status', 'pending')->first();
        if ($check_avilability) {
            return redirect()->route('outward.index')->with('msg', 'Your Previous Outward are Not Approved');
        }

        $year = date('Y');
        $max_serial_number = Outward::max('outward_number');
        $last_serial_number = substr($max_serial_number, -4);
        $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        $outward_id = 'OT' . $year . $next_serial_number;
        $sales_order = SalesOrder::where('id', $request->so_id)->first();
        $company = Company::where('id', $sales_order->company_id)->first();
        $warehouse = WareHouseModel::where('id', $request->warehouse_id)->first();
        $item_category = Category::get();
        // dd($warehouse);

        $company_setting = CompanySetting::first();

        $so_item = SoItem::Join('categories', 'categories.id', '=', 'so_items.item_category')
            ->Join('subcategories', 'subcategories.id', '=', 'so_items.item_subcategory')
            ->select(
                'categories.*',
                'subcategories.*',
                'so_items.*',
                'so_items.id as so_item_main_id',
            )
            ->where('sale_id', $request->so_id)
            ->get();
        // dd( $so_item);


        $so_items = SoItem::leftJoin('categories', 'categories.id', '=', 'so_items.item_category')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'so_items.item_subcategory')
            ->leftJoin('stock_items', function ($join) {
                $join->on('stock_items.category_id', '=', 'so_items.item_category')
                    ->on('stock_items.sub_category_id', '=', 'so_items.item_subcategory')
                    ->on('stock_items.length', '=', 'so_items.length');
            })
            ->select(
                'so_items.*',
                'so_items.weight as so_weight',
                'so_items.rest_pcs as rest_pcs',
                'so_items.id as so_item_id',
                'categories.*',
                'subcategories.*',
                'stock_items.id as stock_item_id',
                'stock_items.warehouse_id',
                'stock_items.length as stock_length',
                'stock_items.weight as stock_weight',
                // 'stock_items.uom_type as stock_uom_type',
                'stock_items.piece as stock_piece',
                // 'stock_items.quantity as stock_quantity',
                // DB::raw('COALESCE(stock_items.current_quantity, 0) as current_quantity'),
                'stock_items.created_at as stock_created_at',
                'stock_items.updated_at as stock_updated_at'
            )
            ->where('so_items.sale_id', $sales_order->id)
            ->where('stock_items.warehouse_id', $request->warehouse_id)
            ->get();




        $so_item_ids = $so_items->pluck('so_item_id')->toArray();
        // dd($so_item_ids);

        // Filter $so_item to get the items that are not in $so_item_ids
        $extra_items = $so_item->filter(function ($item) use ($so_item_ids) {
            return !in_array($item->so_item_main_id, $so_item_ids);
        });
        // dd( $extra_items);


        $data = [
            'outward_id' => $outward_id,
            'sales_order' => $sales_order,
            'outward_type' => 'For Sales Order',
            'company' => $company,
            'company_setting' => $company_setting,
            'warehouse' => $warehouse,
            'category' => $item_category,
            'so_items' => $so_items,
            'extra_items' => $extra_items,
        ];

        return view('outward.create_sooutward')->with($data);
    }

    public function store_so(Request $request)
    {
        if (!$request->category_id) {
            return redirect()->route('outward.index')->with('msg', 'No Item Selected');
        }
        $outward = new Outward();
        $outward->outward_number = $request->outward_id;
        $outward->company_id = $request->company_id;
        $outward->date = $request->date;
        $outward->vehicle_number = $request->vehicle_number;
        $outward->warehouse_id = $request->warehouse_id;
        $outward->total_weight = $request->total_weight;
        $outward->type = $request->type;
        $outward->status = 'pending';
        $outward->bill_status = 'bill pending';
        $outward->additional_charges = $request->additional_charges;
        $outward->loading_charges = $request->loading_charges;
        $outward->freight = $request->freight;
        $outward->so_id = $request->so_id;
        $outward->supervisor = $request->supervisor;
        // $outward->remarks = $request->remarks;
        $outward->save();
        $id = $outward->id;

        if ($id) {
            for ($i = 0; $i < count($request->category_id); $i++) {

                $so_items = SoItem::where('id', $request->so_item_id[$i])->first();
                $outward_items = OutwardItem::where('so_item_id', $so_items->id)->sum('piece');
                // dd($outward_items);
                if ($outward_items == 0) {
                    $main_exceed = ($so_items->rest_pcs - $request->pcs[$i]);
                    if ($main_exceed < 0) {
                        $exceed_value = abs($main_exceed);
                        // OutwardItem::where('so_item_id',  $request->so_item_id[$i])->update(['exceed_pcs',  $exceed_value]);
                    }
                } else {
                    if ($so_items->rest_pcs < ($request->pcs[$i])) {
                        // $exceed_value = (($outward_items + $request->pcs[$i]) - $so_items->rest_pcs);
                        $exceed_value_temp = ($so_items->rest_pcs - ($request->pcs[$i]));
                        $exceed_value = abs($exceed_value_temp);
                        // OutwardItem::where('so_item_id',  $request->so_item_id[$i])->update(['exceed_pcs',  $exceed_value]);
                    }
                }
                // dd($exceed_value);

                $update = SoItem::where('id', $request->so_item_id[$i])
                    ->update([
                        'rest_pcs' => DB::raw('GREATEST(rest_pcs - ' . $request->pcs[$i] . ', 0)'),
                    ]);

                $outwardItem = new OutwardItem();
                $outwardItem->category_id = $request->category_id[$i];
                $outwardItem->sub_category_id = $request->subcategory_id[$i];
                // $outwardItem->quantity = $request->qty[$i];
                $outwardItem->length = $request->length[$i];
                $outwardItem->uom_type = $request->uom_type[$i] ?? 'off';
                $outwardItem->piece = $request->pcs[$i];
                $outwardItem->weight = $request->weight[$i];
                $outwardItem->so_item_id = $request->so_item_id[$i];
                // $outward->remarks = $request->remarks;
                $outwardItem->outward_id = $id;
                $outwardItem->exceed_pcs = $exceed_value ?? 0;
                $outwardItem->save();
            }
        }
        $outward = Outward::where('id', $id)->first();
        $so_items = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
            ->join('subcategories', 'subcategories.category_id', '=', 'categories.id')
            ->select('*', 'so_items.weight as so_weight', 'so_items.id as so_item_id')
            ->where('sale_id', $request->so_id)->get();

        if (!empty($outward)) {
            $outward_item = OutwardItem::where('outward_id', $outward->id)->first();
            $outward_item_ids = $outward_item->pluck('so_item_id')->toArray();
            $extra_items = SoItem::where('sale_id', $request->so_id)->sum('rest_pcs');
            if ($extra_items != 0) {
                SalesOrder::where('id', $request->so_id)->update(['status' => 'partial pending']);
            }
            // } else {
            //     SalesOrder::where('id', $request->so_id)->update(['status' => 'closed']);
            // }
            return redirect()->route('outward.index')->with('success', 'outward created Successfully');
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $outward = new Outward();
        $outward->outward_number = $request->outward_id;
        $outward->company_id = $request->company_id;
        $outward->date = $request->date;
        $outward->supplier_id = $request->supplier_id;
        $outward->so_number = $request->so_number;
        $outward->total_weight = $request->total_weight;
        $outward->type = $request->type;
        $outward->status = 'pending';
        $outward->bill_status = 'bill pending';
        // $outward->remarks = $request->remarks;
        $outward->save();
        $id = $outward->id;

        if ($id) {
            for ($i = 0; $i < count($request->item_category); $i++) {
                $outwardItem = new OutwardItem();
                $outwardItem->category_id = $request->item_category[$i];
                $outwardItem->sub_category_id = $request->item_sub_category[$i];
                $outwardItem->weight = $request->weight[$i];
                $outwardItem->outward_id = $id;
                $outwardItem->save();
            }


            return redirect()->route('outward.index')->with('success', 'Outward created Successfully');
        }
    }


    public function delete($id)
    {
        $outward = Outward::where('id', $id)->first();
        if ($outward->type == 'direct') {
            Outward::where('id', $id)->delete();
            OutwardItem::where('outward_id', $id)->delete();
        } else {
            $outward_items = OutwardItem::where('outward_id', $id)->get();
            $sales_order = SalesOrder::where('id', $outward->so_id)->first();
            foreach ($outward_items as $outward_item) {
                $so_item = SoItem::where('id', $outward_item->so_item_id)->first();
                $rest = ($outward_item->piece + $so_item->rest_pcs);
                // dd( $rest );
                if (($outward_item->exceed_pcs == 0)) {
                    SoItem::where('id', $outward_item->so_item_id)->update(['rest_pcs' => $rest]);
                } else {
                    $main_rest = ($outward_item->piece - $outward_item->exceed_pcs);
                    SoItem::where('id', $outward_item->so_item_id)->update(['rest_pcs' => $main_rest]);
                }
            }
            Outward::where('id', $id)->delete();
            OutwardItem::where('outward_id', $id)->delete();
            $extra_items = Outward::where('so_id', $id)->get();
            if ($extra_items->isNotEmpty()) {
                SalesOrder::where('id', $sales_order->id)->update(['status' => 'pending']);
            } else {
                SalesOrder::where('id', $sales_order->id)->update(['status' => 'partial pending']);
            }
        }
        return redirect()->route('outward.index')->with('delete', 'Outward Deleted Successfully');
    }

    public function approve($id)
    {
        $outward_data = Outward::join('outward_items', 'outwards.id', '=', 'outward_items.outward_id')
            ->where('outwards.id', $id)
            ->get();


        // dd($outward_data);

        // Loop through each inward data item
        foreach ($outward_data as $data) {
            $supplier_id = $data->supplier_id;
            $category_id = $data->category_id;
            $sub_category_id = $data->sub_category_id;



            // Check if there's an existing stock item that matches the criteria
            $existingStockItem = StockItem::where('supplier_id', $supplier_id)
                ->where('category_id', $category_id)
                ->where('sub_category_id', $sub_category_id)
                ->first();

            // dd($existingStockItem);
            if ($existingStockItem) {
                $existingStockItem->weight -= $data->weight;
                $existingStockItem->save();
            } else {
                return redirect()->route('outward.index')->with('msg', 'Your Outward Item is not in Stock');
            }
        }

        // Update the status of the inward record
        Outward::where('id', $id)->update(['status' => 'Approved']);
        return redirect()->route('outward.index')->with('approve', 'Outward approved Successfully.');
    }


    public function edit($id)
    {
        $outward_data = Outward::where('id', $id)->first();
        $company = Company::where('id', $outward_data->company_id)->first();
        $sales_order = SalesOrder::where('id', $outward_data->so_id)->first();
        $category = Category::all();

        // $sub_category = [];

        // foreach ($category as $categorys) {
        //     $subcategory = SubCategory::where('category_id', $categorys->id)->first();
        //     $sub_category[] = $subcategory;
        // }

        $sub_category = [];

        foreach ($category as $categorys) {
            $subcategory = SubCategory::where('category_id', $categorys->id)->first();
            if ($subcategory !== null) {
                $sub_category[] = $subcategory;
            }
        }

        if ($outward_data->type == 'direct') {
            $outward_item = OutwardItem::leftJoin('categories', 'categories.id', '=', 'outward_items.category_id')
                ->leftJoin('subcategories', 'subcategories.id', '=', 'outward_items.sub_category_id')
                ->leftJoin('stock_items', function ($join) {
                    $join->on('stock_items.category_id', '=', 'outward_items.category_id')
                        ->on('stock_items.sub_category_id', '=', 'outward_items.sub_category_id')
                        ->on('stock_items.length', '=', 'outward_items.length');
                })
                ->select(
                    'outward_items.*',
                    'outward_items.weight as outward_weight',
                    'categories.*',
                    'subcategories.*',
                    'stock_items.piece as stock_piece',
                )
                ->where('outward_items.outward_id', $id)
                ->where('stock_items.warehouse_id', $outward_data->warehouse_id)
                ->get();

            $warehouse = WareHouseModel::where('id', $outward_data->warehouse_id)->first();
            $item_category = Category::get();
            $count = OutwardItem::where('outward_id', $id)->count();

            $data = [
                'outward_id' => $outward_data->outward_number,
                'outard_type' => $outward_data->type,
                'outward_data' => $outward_data,
                'outward_item' => $outward_item,
                'count' => $count,
                'company' => $company,
                'category' => $item_category,
                'warehouse' => $warehouse,
                'sub_category' => $sub_category,
                // 'remarks'=> $remarks,
            ];
            return view('outward.edit')->with($data);
        } else {
            // Retrieve the collections
            $outward_item = OutwardItem::leftJoin('categories', 'categories.id', '=', 'outward_items.category_id')
                ->leftJoin('subcategories', 'subcategories.id', '=', 'outward_items.sub_category_id')
                ->leftJoin('so_items', 'so_items.id', '=', 'outward_items.so_item_id')
                ->leftJoin('stock_items', function ($join) {
                    $join->on('stock_items.category_id', '=', 'outward_items.category_id')
                        ->on('stock_items.sub_category_id', '=', 'outward_items.sub_category_id')
                        ->on('stock_items.length', '=', 'outward_items.length');
                })
                ->select(
                    'outward_items.*',
                    'outward_items.weight as outward_weight',
                    'outward_items.so_item_id as so_item_id',
                    'outward_items.exceed_pcs as exceed_pcs',
                    'so_items.*',
                    'so_items.rest_pcs as rest_pcs',
                    'categories.*',
                    'subcategories.*',
                    'stock_items.id as stock_item_id',
                    'stock_items.warehouse_id',
                    'stock_items.length as stock_length',
                    'stock_items.weight as stock_weight',
                    'stock_items.piece as stock_piece',
                    'stock_items.created_at as stock_created_at',
                    'stock_items.updated_at as stock_updated_at'
                )
                ->where('outward_items.outward_id', $id)
                ->where('stock_items.warehouse_id', $outward_data->warehouse_id)
                ->get();
            // dd( $outward_item);

            $so_item = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
                ->join('subcategories', 'subcategories.id', '=', 'so_items.item_subcategory')
                ->select(
                    'so_items.*',
                    'so_items.id as so_item_id',
                    'categories.*',
                    'subcategories.*',
                )
                ->where('sale_id', $outward_data->so_id)
                ->get();

            $outward_item_ids = $outward_item->pluck('so_item_id')->toArray();
            $extra_items = $so_item->filter(function ($item) use ($outward_item_ids) {
                return !in_array($item->so_item_id, $outward_item_ids); // comparing item id with outward_item so_item_id
            });


            $warehouse = WareHouseModel::where('id', $outward_data->warehouse_id)->first();
            $item_category = Category::get();
            $count = OutwardItem::where('outward_id', $id)->count();

            $data = [
                'outward_id' => $outward_data->outward_number,
                'outard_type' => $outward_data->type,
                'outward_data' => $outward_data,
                'outward_item' => $outward_item,
                'count' => $count,
                'company' => $company,
                'category' => $item_category,
                'warehouse' => $warehouse,
                'sales_order' => $sales_order,
                'extra_items' => $extra_items
            ];

            return view('outward.edit_sooutward', )->with($data);
        }
    }

    public function update(Request $request, $id)
    {

        // dd( $request);

        $outward_check = Outward::where('id', $id)->first();
        if ($outward_check->status == 'Approved') {
            return redirect()->back()->with('msg', 'Your Outward are already approved');
        }

        $data = [
            'date' => $request->date,
            'vehicle_number' => $request->vehicle_number,
            'additional_charges' => $request->additional_charges,
            'loading_charges' => $request->loading_charges,
            'freight' => $request->freight,
            'supervisor' => $request->supervisor,
            'total_weight' => $request->total_weight,
        ];
        $outward_data = Outward::where('id', $id)->update($data);
        OutwardItem::where('outward_id', $id)->delete();
        if ($id) {
            //SO Item Code
            for ($i = 0; $i < count($request->item_category); $i++) {
                $outwardItem = new OutwardItem();
                $outwardItem->category_id = $request->item_category[$i];
                $outwardItem->sub_category_id = $request->item_sub_category[$i];
                $outwardItem->quantity = $request->quantity[$i];
                $outwardItem->length = $request->length[$i];
                $outwardItem->uom_type = $request->uom[$i];
                $outwardItem->piece = $request->piece[$i];
                $outwardItem->weight = $request->weight[$i];
                $outwardItem->outward_id = $id;
                $outwardItem->save();
            }
            return redirect()->route('outward.index')->with('update', 'Outward Updated Successfully');
        }
    }

    public function soupdate(Request $request, $id)
    {
        $outward_check = Outward::where('id', $id)->first();
        if ($outward_check->status == 'Approved') {
            return redirect()->back()->with('msg', 'Your Outward are already approved');
        }

        for ($i = 0; $i < count($request->category_id); $i++) {

            // Get the first OutwardItem that does not match the current category_id and has the given outward_id
            $ot_item = OutwardItem::where('category_id', '!=', $request->category_id[$i])
                ->where('outward_id', $id)
                ->first();
            //    dd( $ot_item);

            if ($ot_item) {
                // Calculate the rest_notselect value
                $rest_notselect = $ot_item->piece - $ot_item->exceed_pcs;

                // Update the SoItem with the calculated rest_notselect value
                SoItem::where('id', $ot_item->so_item_id)
                    ->update(['rest_pcs' => $rest_notselect]);
            }
        }



        $data = [
            'date' => $request->date,
            'vehicle_number' => $request->vehicle_number,
            'additional_charges' => $request->additional_charges,
            'loading_charges' => $request->loading_charges,
            'freight' => $request->freight,
            'supervisor' => $request->supervisor,
            'total_weight' => $request->total_weight,
        ];
        $outward_data = Outward::where('id', $id)->update($data);
        OutwardItem::where('outward_id', $id)->delete();
        if ($id) {
            for ($i = 0; $i < count($request->category_id); $i++) {
                $so_items = SoItem::where('id', $request->so_item_id[$i])->first();
                $outward_items = OutwardItem::where('so_item_id', $so_items->id)->sum('piece');
                // dd($outward_items);
                if ($outward_items == 0) {
                    $main_exceed = ($so_items->pcs - $request->pcs[$i]);
                    if ($main_exceed < 0) {
                        $exceed_value = abs($main_exceed);
                        // OutwardItem::where('so_item_id',  $request->so_item_id[$i])->update(['exceed_pcs',  $exceed_value]);
                    } else {
                        $exceed_value = 0;
                    }
                } else {
                    if ($so_items->rest_pcs < $request->pcs[$i]) {
                        if ($so_items->rest_pcs == 0) {
                            $exceed_value_temp = ($so_items->pcs - ($request->pcs[$i] + $outward_items));
                            $exceed_value = abs($exceed_value_temp);
                        } else {
                            $exceed_value_temp = ($so_items->rest_pcs - ($request->pcs[$i]));
                            $exceed_value = abs($exceed_value_temp);
                        }
                    }
                }
                // dd( $exceed_value); 

                $outward_item = OutwardItem::where('category_id', $request->category_id[$i])->where('sub_category_id', $request->subcategory_id[$i])->where('length', $request->length[$i])->where('so_item_id', $request->so_item_id[$i])->sum('piece');
                $main_value = ($request->pcs[$i] + $outward_item);
                $update = SoItem::where('id', $request->so_item_id[$i])
                    ->update([
                        'rest_pcs' => DB::raw('GREATEST(pcs - ' . $main_value . ', 0)'),
                    ]);

                $outwardItem = new OutwardItem();
                $outwardItem->category_id = $request->category_id[$i];
                $outwardItem->sub_category_id = $request->subcategory_id[$i];
                $outwardItem->length = $request->length[$i];
                $outwardItem->piece = $request->pcs[$i];
                $outwardItem->weight = $request->weight[$i];
                $outwardItem->outward_id = $id;
                $outwardItem->so_item_id = $request->so_item_id[$i];
                $outwardItem->exceed_pcs = $exceed_value ?? 0;
                $outwardItem->save();
            }

            // $new_outward = Outward::where('id',  $id)->first();
            // $new_so_item = SoItem::where('sale_id',  $new_outward->so_id)->where('rest_pcs', '!=', '0')->first();
            // if(!empty($new_so_item)){
            //    SalesOrder::where('id', $new_outward->so_id)->update(['status'=> 'partial pending']);
            // }else{
            //    SalesOrder::where('id', $new_outward->so_id)->update(['status'=> 'closed']);
            // }
            return redirect()->route('outward.index')->with('update', 'Outward Updated Successfully');

        }
    }

    public function bill(Request $request)
    {
        Outward::where('id', $request->id)->update(['bill_status' => $request->bill_status]);
        return redirect()->route('outward.index')->with('update', 'Outward Update  Successfully');
    }


    public function virtual_store(Request $request)
    {
        $item_id = $request->item_id;
        $sub_category_id = $request->item_sub_category;
        $seller_id = $request->seller_id;
        // dd($item_id, $sub_category_id, $seller_id);
        $data = StockItem::where('supplier_id', $seller_id)
            ->where('category_id', $item_id)
            ->where('sub_category_id', $sub_category_id)
            ->first();

        return response([
            'data' => $data,
        ]);
    }
    public function so_number(Request $request)
    {
        $buyer_id = $request->buyer_id;
        // dd($item_id, $sub_category_id, $seller_id);
        $data = SalesOrder::where('company_id', $buyer_id)
            ->get();

        return response([
            'data' => $data,
        ]);
    }
}
