<?php

namespace App\Http\Controllers;

use App\Livewire\Warehouse;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\StockItem;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\GstSetting;
use App\Models\QtItem;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\SoItem;
use App\Models\SubCategory;
use App\Models\WareHouseModel;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Sales-index', ['only' => ['index']]);
        $this->middleware('permission:Sales-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:Sales-view', ['only' => ['edit']]);
        $this->middleware('permission:Sales-edit', ['only' => ['update']]);
        $this->middleware('permission:Sales-delete', ['only' => ['delete']]);
        $this->middleware('permission:Sales-close', ['only' => ['close']]);
    }
 
    public function index()
    {
        $sales_order = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
                                ->join('so_items','so_items.so_id','=','sales_orders.id')
                                ->join('categories','categories.id','=','so_items.item_category')
                                ->join('subcategories','subcategories.id','=','so_items.item_subcategory')
            ->select('*', 'sales_orders.id as id','so_items.*','categories.name as category_name','subcategories.sub_category as sub_category_name')
            // ->where('sales_orders.status', '!=', 'closed')

            ->orderBy('sales_orders.id', 'desc')
            ->get();
        $company = Company::where('type', 'buyer')->get();
        $supplier_company = Company::where('type', 'supplier')->get();
        $warehouse = WareHouseModel::all();
        $data = [
            'sales_order' => $sales_order,
            'company' => $company,
            'warehouse' => $warehouse,
            'supplier_company' => $supplier_company,
        ];

        // dd($sales_order);

        return view('sales.index')->with($data);
    }

    public function create(Request $request)
    {
        $year = date('Y');
        $max_serial_number = SalesOrder::all()->max('so_number');
        $last_serial_number = substr($max_serial_number, -4);
        $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        $so_number = 'SO' . $year . $next_serial_number;
        $company = Company::where('id', $request->company_id)->first();
        $category = Category::all();
        $custom_due_date = CompanySetting::first();
        $gstsetting = GstSetting::all();
        $data = [
            'company' => $company,
            'category' => $category,
            'gstsetting' => $gstsetting,
            'so_number' => $so_number,
            'custom_due_date' => $custom_due_date,


        ];
        // dd($data);
        return view('sales.create')->with($data);
    }



    public function store(Request $request)
    {
 
        $salesOrder = new SalesOrder();
        $salesOrder->company_id = $request->company_id;
        $salesOrder->address = $request->address;
        $salesOrder->date = $request->date;
        $salesOrder->due_date = $request->due_date;
        $salesOrder->terms_condition = $request->terms_condition;
        $salesOrder->total_quantity = $request->total_quantity;
        $salesOrder->total_amount = $request->total_amount;
        
        $salesOrder->total_price = $request->total_price;
        
        $salesOrder->rest_quantity = $request->total_quantity;
        $salesOrder->status = 'pending';
        $salesOrder->match_position = 'open';
        
        $salesOrder->so_number = $request->so_number;
        // $salesOrder->document_file = $request->document_file;
        $salesOrder->document_file = 'uploads/documents/sales/' . $request->so_number . '/' . $request->so_number . '.pdf';
        
        $salesOrder->save();
        $id = $salesOrder->id;
        

        if ($id) {
            for ($i = 0; $i < count($request->unit_price_); $i++) {
                $soItem = new SoItem();
                $soItem->item_category = $request->item_category[$i];
                $soItem->item_subcategory = $request->item_subcategory[$i];
                $soItem->qty = $request->qty[$i];
                $soItem->so_rest_qty = $request->qty[$i];
                $soItem->unit_price = $request->unit_price_[$i];
                $soItem->price = $request->price[$i];
                $itemSerial = str_pad($i + 1, 2, '0', STR_PAD_LEFT); 
                $soItem->so_item_no = $salesOrder->so_number . '-' . $itemSerial; 
                $soItem->so_id = $id;
                // dd($soItem);
                $soItem->save();
            }
            return redirect()->route('sales.index')->with('success', 'Sales Orders Created Successfully');
            ;
            // return redirect()->route('sales.pdf',$id)->with('success', 'Sales Orders Created Successfully');;
            // return redirect()->route('sales.pdf', $id)->with('success', 'Sales Orders Created Successfully');
        }
    }

    public function delete($id)
    {

        $sales_order = SalesOrder::where('id', $id)->first();
        Quotation::where('id', $sales_order->qt_id)->update(['status' => 'pending']);
        SalesOrder::where('id', $id)->delete();
        SoItem::where('sale_id', $id)->delete();
        return redirect()->route('sales.index')->with('delete', 'Sales Orders Updated Successfully');
    }

    public function close(Request $request)
    {

        $data = [
            'status' => $request->so_close_type,
        ];
        SalesOrder::where('id', $request->id)->update($data);
        return redirect()->route('sales.index')->with('update', 'Sales Orders Updated Successfully');
    }

    public function edit($id)
    {
        $sales_order = SalesOrder::join('warehouse', 'warehouse.id', '=', 'sales_orders.warehouse_id')
            ->where('sales_orders.id', $id)
            ->select('sales_orders.*', 'sales_orders.id as so_id', 'warehouse.*')
            ->first();
        $warehouse = WareHouseModel::all();
        $company = Company::where('id', $sales_order->company_id)->first();
        $category = Category::all();
        $gstsetting = GstSetting::all();

        if ($sales_order->so_type == 'direct') {

            $sub_category = [];

            foreach ($category as $categorys) {
                $subcategory = SubCategory::where('category_id', $categorys->id)->first();
                $sub_category[] = $subcategory;
            }
            // dd( $sub_category);

            $so_item = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
                ->join('subcategories', 'so_items.item_subcategory', '=', 'subcategories.id')
                ->select('*', 'so_items.weight as so_weight', 'so_items.price as so_price')
                ->where('sale_id', $id)->get();

            $count = SoItem::where('sale_id', $id)->count();
            $data = [
                'sales_orders' => $sales_order,
                'so_items' => $so_item,
                'category' => $category,
                'sub_category' => $sub_category,
                'company' => $company,
                'count' => $count,
                'gstsetting' => $gstsetting,
                'warehouse' => $warehouse,
            ];
            // dd($data);

            return view('sales.edit')->with($data);
        } else {
            // $so_item =  SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
            // ->join('subcategories', 'so_items.item_subcategory', '=', 'subcategories.id')
            // ->join('sales_orders', 'sales_orders.id', '=', 'so_items.sale_id')
            // ->join('quotations', 'quotations.id', '=', 'sales_orders.qt_id')
            // ->join('qt_items', 'qt_items.qt_id', '=', 'quotations.id')
            // ->select('*', 'so_items.weight as so_weight', 'so_items.price as so_price')
            // ->where('sale_id', $id)->get();

            $so_item = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
                ->join('subcategories', 'so_items.item_subcategory', '=', 'subcategories.id')
                ->join('sales_orders', 'sales_orders.id', '=', 'so_items.sale_id')
                ->join('quotations', 'quotations.id', '=', 'sales_orders.qt_id')
                ->join('qt_items', 'qt_items.qt_id', '=', 'quotations.id')
                ->select(
                    'so_items.id',
                    'so_items.weight as so_weight',
                    'so_items.price as so_price',
                    'so_items.*',
                    'categories.name as name',
                    'subcategories.sub_category as sub_category'
                )
                ->where('so_items.sale_id', $id)
                ->distinct()
                ->get();

            // dd($so_item);



            $quotation = Quotation::where('id', $sales_order->qt_id)->first();
            $data = [
                'sales_orders' => $sales_order,
                'so_items' => $so_item,
                'category' => $category,
                'company' => $company,
                'gstsetting' => $gstsetting,
                'quotation' => $quotation,
                'warehouse' => $warehouse,
            ];
            // dd( $data);

            return view('sales.edit_forquotation')->with($data);
        }
    }

    public function update(Request $request, $id)
    {
        $sales_order = SalesOrder::where('id', $id)->first();

        if ($sales_order->status != 'pending') {
            return redirect()->back()->with('msg', 'Your Sales Order are Not Pending');
        }
        if ($sales_order->so_type == 'quotation') {
            $data = [
                'date' => $request->date,
                'payment_mandatory' => $request->payment_mandatory,
                'remarks' => $request->remarks,
                'so_type' => $sales_order->so_type,
                'warehouse_id' => $request->warehouse_id,
            ];
            SalesOrder::where('id', $id)->update($data);
            SoItem::where('sale_id', $id)->update(['warehouse_id' => $request->warehouse_id]);
            // return redirect()->route('sales.index');
            return redirect()->route('sales.pdf', $id);
        } else {
            $data = [
                'date' => $request->date,
                'payment_mandatory' => $request->payment_mandatory,
                'total_weight' => $request->total_weight,
                'total_pcs' => $request->total_pcs,
                'sub_total' => $request->sub_total,
                // 'total_sgst' => $request->total_sgst,
                // 'total_cgst' => $request->total_cgst,
                // 'total_igst' => $request->total_igst,
                'total_sgst' => isset($request->total_sgst) && is_numeric($request->total_sgst) ? $request->total_sgst : 0,
                'total_cgst' => isset($request->total_cgst) && is_numeric($request->total_cgst) ? $request->total_cgst : 0,
                'total_igst' => isset($request->total_igst) && is_numeric($request->total_igst) ? $request->total_igst : 0,
                'additional_charges' => $request->additional_charges,
                'loading_charges' => $request->loading_cutting,
                'freight' => $request->freight,
                'grand_total' => $request->grand_total,
                'remarks' => $request->remarks,
                'warehouse_id' => $request->warehouse_id,
                // 'document_file' => $request->document_file,
            ];
            SalesOrder::where('id', $id)->update($data);
            SoItem::where('sale_id', $id)->delete();

            if ($id) {
                // SO Item Code
                for ($i = 0; $i < count($request->amount); $i++) {
                    $stock = StockItem::where('category_id', $request->item_category[$i])
                        ->where('sub_category_id', $request->item_subcategory[$i])
                        ->where('length', $request->length[$i])
                        ->where('warehouse_id', $request->warehouse_id)
                        ->first();

                    if (!$stock) {
                        $stock_data = [
                            'category_id' => $request->item_category[$i],
                            'sub_category_id' => $request->item_subcategory[$i],
                            'length' => $request->length[$i],
                            'warehouse_id' => $request->warehouse_id,
                            'piece' => 0,
                            'weight' => 0,
                        ];
                        StockItem::create($stock_data);
                    }

                    $soItem = new SoItem();
                    $soItem->item_category = $request->item_category[$i];
                    $soItem->item_subcategory = $request->item_subcategory[$i];
                    $soItem->qty = $request->qty[$i];
                    $soItem->length = $request->length[$i];
                    $soItem->uom_type = $request->uom[$i];
                    $soItem->price = $request->price[$i] ?? null;
                    $soItem->pcs = $request->pcs[$i] ?? null;
                    $soItem->rest_pcs = $request->pcs[$i] ?? null;
                    $soItem->weight = $request->weight[$i] ?? null;
                    $soItem->amount = $request->amount[$i] ?? null;
                    $soItem->warehouse_id = $request->warehouse_id;
                    $soItem->sgst = $request->sgst[$i] ?? 0; // Default to 0 if sgst is null
                    $soItem->cgst = $request->cgst[$i] ?? 0; // Default to 0 if cgst is null
                    $soItem->igst = $request->igst[$i] ?? 0; // Default to 0 if igst is null
                    $soItem->gst_percent = $request->gst_percent[$i];
                    $soItem->sale_id = $id;
                    $soItem->save();
                }

                // return redirect()->route('sales.index')->with('update', 'Sales Orders Updated Successfully');
                return redirect()->route('sales.pdf', $id);
            }

        }
    }

    public function show($id)
    {
        $sales_data = SalesOrder::where('id', $id)->first();
        $company_data = Company::where('id', $sales_data->company_id)->first();
        $so_item = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
            ->join('subcategories', 'so_items.item_subcategory', '=', 'subcategories.id')
            ->select(
                'so_items.*',
                'categories.name as name',
                'subcategories.sub_category as sub_category'
            )
            ->where('so_items.sale_id', $id)
            ->get();
        // dd($sales_data, $so_item);
        return view('sales.show', compact('sales_data', 'company_data', 'so_item'));

    }
    public function get_quotation_details($id)
    {
        $quotations = Quotation::where('company_id', $id)->where('status', 'pending')->get(['document_number']); // Sirf document_number field ko select karein
        $documentNumbers = $quotations->pluck('document_number');
        return response()->json([
            'data' => $documentNumbers,
        ]);
    }

    public function get_sales_details($id)
    {
        $sales_orders = SalesOrder::where('company_id', $id)
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere('status', 'partial pending');
            })
            ->get(['id', 'so_number', 'remarks']); // Select both id and so_number

        // Prepare the data array
        $data = $sales_orders->map(function ($order) {
            return [
                'so_id' => $order->id,
                'documentNumber' => $order->so_number,

            ];
        });
        // Return JSON response
        return response()->json([
            'data' => $data
        ]);
    }


    public function create_quotation(Request $request)
    {
        $year = date('Y');
        $max_serial_number = SalesOrder::all()->max('so_number');
        $last_serial_number = substr($max_serial_number, -4);
        $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        $next_document = 'SO' . $year . $next_serial_number;
        $quotations = Quotation::where('document_number', $request->quotation_number)->first();
        $company = Company::where('id', $request->company_id)->first();
        $category = Category::all();
        $gstsetting = GstSetting::all();

        $qt_item = QtItem::join('categories', 'categories.id', '=', 'qt_items.item_category')
            ->join('subcategories', 'subcategories.id', '=', 'qt_items.item_subcategory')
            ->select('*', 'qt_items.weight as qt_weight', 'qt_items.price as qt_price')
            ->where('qt_id', $quotations->id)->get();

        $data = [
            'company' => $company,
            'category' => $category,
            'so_type' => 'quotation',
            'gstsetting' => $gstsetting,
            'quotation' => $quotations,
            'next_document' => $next_document,
            'qt_item' => $qt_item,
        ];
        return view('sales.create_forquotation')->with($data);
    }
    public function store_quotation(Request $request)
    {
        // dd($request);
        $salesOrder = new SalesOrder();
        $salesOrder->qt_id = $request->qt_id;
        $salesOrder->company_id = $request->company_id;
        $salesOrder->address = $request->address;
        $salesOrder->date = $request->date;
        $salesOrder->payment_mandatory = $request->payment_mandatory;
        $salesOrder->total_weight = $request->total_weight;
        $salesOrder->total_pcs = $request->total_pcs;
        $salesOrder->status = 'pending';
        $salesOrder->sub_total = $request->sub_total;
        $salesOrder->total_sgst = $request->total_sgst;
        $salesOrder->total_cgst = $request->total_cgst;
        $salesOrder->total_igst = $request->total_igst;
        $salesOrder->additional_charges = $request->additional_charges;
        $salesOrder->loading_charges = $request->loading_cutting;
        $salesOrder->freight = $request->freight;
        $salesOrder->grand_total = $request->grand_total;
        $salesOrder->remarks = $request->remarks;
        $salesOrder->so_type = $request->so_type;
        $salesOrder->gst_type = $request->gst_type;
        $salesOrder->so_number = $request->document_number;
        $salesOrder->warehouse_id = $request->warehouse_id;
        $salesOrder->document_file = 'uploads/documents/sales/' . $request->document_number . '/' . $request->document_number . '.pdf';
        $salesOrder->save();
        $id = $salesOrder->id;

        if ($id) {
            // SO Item Code
            for ($i = 0; $i < count($request->amount); $i++) {
                $stock = StockItem::where('category_id', $request->category_id[$i])
                    ->where('sub_category_id', $request->subcategory_id[$i])
                    ->where('length', $request->length[$i])
                    ->where('warehouse_id', $request->warehouse_id)
                    ->first();

                if (!$stock) {
                    $stock_data = [
                        'category_id' => $request->category_id[$i],
                        'sub_category_id' => $request->subcategory_id[$i],
                        'length' => $request->length[$i],
                        'warehouse_id' => $request->warehouse_id,
                        'piece' => 0,
                        'weight' => 0,
                    ];
                    StockItem::create($stock_data);
                }

                $soItem = new SoItem();
                $soItem->item_category = $request->category_id[$i];
                $soItem->item_subcategory = $request->subcategory_id[$i];
                $soItem->qty = $request->qty[$i];
                $soItem->length = $request->length[$i];
                $soItem->uom_type = $request->uom_type[$i] ?? 'pcs';
                $soItem->price = $request->price[$i] ?? null;
                $soItem->pcs = $request->pcs[$i] ?? null;
                $soItem->rest_pcs = $request->pcs[$i] ?? null;
                $soItem->weight = $request->weight[$i] ?? null;
                $soItem->warehouse_id = $request->warehouse_id;
                $soItem->amount = $request->amount[$i] ?? null;
                $soItem->gst_percent = $request->gst_percent[$i] ?? null;
                $soItem->sgst = $request->sgst[$i] ?? 0; // Default to 0 if sgst is null
                $soItem->cgst = $request->cgst[$i] ?? 0; // Default to 0 if cgst is null
                $soItem->igst = $request->igst[$i] ?? 0; // Default to 0 if igst is null
                $soItem->sale_id = $id;
                $soItem->save();
            }

            Quotation::where('id', $request->qt_id)->update(['status' => 'sales generated']);


            // return redirect()->route('sales.pdf', $id);
            return redirect()->route('sales.pdf', $id);
        }
    }


    public function get_remark(Request $request)
    {
        $so_data = SalesOrder::where('id', $request->id)->first();
        if ($so_data) {
            $so_remark = $so_data->remarks;
        } else {
            $so_remark = '';
        }
        // dd($so_remark);
        return response([
            'remark' => $so_remark
        ]);
    }
    public function closeSales(Request $request)
    {
        // Validate the request
        $data = [
            'remarks' => $request->remarks,
            'status' => $request->so_close_type,
        ];
        // dd($data);
        SalesOrder::where('id', $request->id)->update($data);
        // Redirect or return a response
        return redirect()->back()->with('success', 'Sales order updated successfully.');
    }
    public function getSoRemarkForModal(Request $request)
    {
        $so_id = $request->so_id;
        $salesOrder = SalesOrder::find($so_id);
        return response()->json(['remark' => $salesOrder->remarks]);
    }
}