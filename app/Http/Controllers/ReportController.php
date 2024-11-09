<?php

namespace App\Http\Controllers;

use App\Livewire\Warehouse;
use App\Models\Ageing;
use App\Models\Category;
use App\Models\Company;
use App\Models\Dispatch;
use App\Models\Inward;
use App\Models\Outward;
use App\Models\Quotation;
use App\Models\Report;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\StockItem;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\WareHouseModel;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;


use Illuminate\Http\Request;

class ReportController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:PO-Report', ['only' => ['po_report', 'get_po_report']]);
        $this->middleware('permission:SO-Report', ['only' => ['so_report', 'get_so_report']]);
        $this->middleware('permission:Quotation-Report', ['only' => ['quotation_execution_report', 'get_quotation_execution_report']]);
        $this->middleware('permission:Inward-Report', ['only' => ['inward_report', 'get_inward_report']]);
        $this->middleware('permission:Outward-Report', ['only' => ['outward_report', 'get_outward_report']]);
        $this->middleware('permission:Stock-Report', ['only' => ['stock_report', 'get_stock_report']]);
        $this->middleware('permission:Ageing-Report', ['only' => ['ageing_report', 'get_ageing_report']]);
        $this->middleware('permission:Stock-Transaction-Report', ['only' => ['stock_transaction_report', 'get_stock_transaction_report']]);
        $this->middleware('permission:Top-Selling-Report', ['only' => ['top_selling_report', 'get_top_selling_report']]);
    }

    // PO Reports

    public function po_report()
    {
        $companys = Company::where('type', 'supplier')->get();
        $Categorys = Category::all();

        return view('reports.po_report', compact('companys', 'Categorys'));
    }


    public function get_po_report(Request $request)
    {
        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        $filterCompany = $request->filterCompany;
        $filterCategory = $request->filterCategory;
        $filterStatus = $request->filterStatus;

        // $query = PurchaseOrder::join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
        //     ->join('categories', 'purchase_orders.category', '=', 'categories.id')
        //     ->leftJoin('inwards', 'purchase_orders.document_number', '=', 'inwards.po_document_number')
        //     ->leftJoin('inward_items', 'inwards.id', '=', 'inward_items.inward_id')
        //     ->select(
        //         'purchase_orders.id as po_id',
        //         'purchase_orders.date as po_date',
        //         'purchase_orders.due_date as due_date',
        //         'purchase_orders.document_number',
        //         'purchase_orders.quantity',
        //         'companies.company_name',
        //         'categories.name',
        //         DB::raw('(purchase_orders.quantity - SUM(inward_items.weight)) as rest_quantity') // Calculation for rest_quantity
        //     )
        //     ->where('inwards.status', 'Approved')
        //     ->whereBetween('purchase_orders.date', [$filterTodate, $filterFromdate])
        //     ->groupBy(
        //         'purchase_orders.id',
        //         'purchase_orders.date',
        //         'purchase_orders.due_date',
        //         'purchase_orders.document_number',
        //         'purchase_orders.quantity',
        //         'companies.company_name',
        //         'categories.name'
        //     )
        //     ->orderBy('purchase_orders.document_number', 'desc');

        if($filterStatus == 'Open'){
            $query = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->Join('po_items', function ($join) {
                $join->on('po_items.po_id', '=', 'purchase_orders.id')
                    ->where('po_items.po_dispatch_item_status', '!=', 'Close');
            })
            ->Join('categories', 'categories.id', '=', 'po_items.item_category')
            ->whereBetween('purchase_orders.date', [$filterTodate, $filterFromdate]);
        }
        else{
            $query = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->Join('po_items', function ($join) {
                $join->on('po_items.po_id', '=', 'purchase_orders.id')
                    ->where('po_items.po_dispatch_item_status', '!=', 'Open');
            })
            ->Join('categories', 'categories.id', '=', 'po_items.item_category')
            ->whereBetween('purchase_orders.date', [$filterTodate, $filterFromdate]);
        }


        if ($filterCompany != 'all') {
            $query->where('companies.id', $filterCompany);
        }

        if ($filterCategory != 'all') {
            $query->where('categories.id', $filterCategory);
        }


        $filteredDatas = $query->get();
       

        $data = [];
        foreach ($filteredDatas as $filteredData) {
            $tempData = [
                'po_id' => $filteredData->po_id,
                'po_document_number' => $filteredData->document_number,
                'po_item_number' => $filteredData->po_item_no,
                'date' => date('d-M-Y', strtotime($filteredData->date)),
                'category' => $filteredData->name,
                'company_name' => $filteredData->company_name,
                'po_unit_price' => $filteredData->unit_price,
                'quantity' => $filteredData->qty,
                'rest_quantity' => $filteredData->po_dispatch_rest_qty ?? 0,
                'dispatch_status' => $filteredData->po_dispatch_item_status,
            ];
            $data[] = $tempData;
            // dd($data);
        }

        return response()->json($data);
    }

    // SO Report
    public function so_report()
    {
        $companys = Company::where('type', 'buyer')->get();
        $Categorys = Category::all();

        // dd($sales_order);
        return view('reports.so_report', compact('companys', 'Categorys'));
    }


    public function get_so_report(Request $request)
    {

        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        $filterCompany = $request->filterCompany;
        $filterCategory = $request->filterCategory;
        $filterStatus = $request->filterStatus;
        // dd($filterTodate, $filterFromdate, $filterCompany, $filterCategory);

        if($filterStatus == 'Open'){
            $query = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->Join('so_items', function ($join) {
                $join->on('so_items.so_id', '=', 'sales_orders.id')
                    ->where('so_items.so_dispatch_item_status', '!=', 'Close');
            })
            ->Join('categories', 'categories.id', '=', 'so_items.item_category')
            ->whereBetween('sales_orders.date', [$filterTodate, $filterFromdate]);
        }else{
            $query = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->Join('so_items', function ($join) {
                $join->on('so_items.so_id', '=', 'sales_orders.id')
                    ->where('so_items.so_dispatch_item_status', '!=', 'Open');
            })
            ->Join('categories', 'categories.id', '=', 'so_items.item_category')
            ->whereBetween('sales_orders.date', [$filterTodate, $filterFromdate]);
        }
   


        if ($filterCompany != 'all') {
            $query->where('sales_orders.company_id', $filterCompany);
        }

        if ($filterCategory != 'all') {
            $query->where('so_items.item_category', $filterCategory);
        }



        $filteredDatas = $query->get();
        // dd($filteredDatas);
        $data = [];
        foreach ($filteredDatas as $filteredData) {
            $tempData = [
                'date' => date('d-M-Y', strtotime($filteredData->date)),
                'so_number' => $filteredData->so_number,
                'so_item_number' => $filteredData->so_item_no,
                'company_name' => $filteredData->company_name,
                'category' => $filteredData->name,
                'quantity' => $filteredData->qty,
                'so_unit_price' => $filteredData->unit_price,
                'rest_qty' => $filteredData->so_dispatch_rest_qty,
                'dispatch_status' => $filteredData->so_dispatch_item_status,
            ];
            $data[] = $tempData;
        }

        return response()->json($data);
    }

    // Quotationso Report
    public function quotationso_report()
    {

        $companys = Company::all();

        return view('reports.quotation_report', compact('companys'));
    }

    public function get_quotationso_report(Request $request)
    {
        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        // $filterCompany = $request->filterCompany;


        $query = Quotation::join('companies', 'quotations.company_id', '=', 'companies.id')
            ->whereBetween('quotations.quotation_date', [$filterTodate, $filterFromdate])
            ->orderBy('quotations.id', 'desc')
            ->select('companies.*', 'quotations.*', 'quotations.id as q_id');

        // if ($filterCompany != 'all') {
        //     $query->where('companies.id', $filterCompany);
        // }


        $quotation_data = $query->get();
        // dd($quotation_data);
        $count = $query->count();
        $so_count = $query->where('status', 'sales generated')->count();

        $execution_rate = ($count > 0 && $so_count > 0) ? ($so_count / $count) * 100 : 0;

        $data = [];
        foreach ($quotation_data as $quotation) {
            $tempData = [
                'date' => date('m-d-Y', strtotime($quotation->quotation_date)),
                // 'qt_number' => $quotation->document_number,
                'qt_number' => '<a href="quotation-edit/' . $quotation->id . '  ">' . $quotation->document_number . '</a>',
                'company_name' => $quotation->company_name,
                'total_pcs' => $quotation->total_pcs,
                'total_weight' => $quotation->total_weight,
                'gst_type' => $quotation->gst_type,
                'loading_charges' => $quotation->loading_cutting,
                'additional_charges' => $quotation->additional_charges,
                'freight_charges' => $quotation->freight_charges,
                'total_sgst' => $quotation->total_sgst,
                'total_cgst' => $quotation->total_cgst,
                'total_igst' => $quotation->total_igst,
                'grand_total' => $quotation->grand_total,
                'status' => $quotation->status,
            ];
            $data[] = $tempData;
        }

        $response = [
            'data' => $data,
            'execution_rate' => $execution_rate,
        ];

        return response()->json($response);
    }


    // Outward Report
    public function outward_report()
    {

        $companys = Company::all();
        $Categorys = Category::all();
        return view('reports.outward_report', compact('companys', 'Categorys'));
    }

    public function get_outward_report(Request $request)
    {


        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        $filterCompany = $request->filterCompany;
        $filterCategory = $request->filterCategory;


        $query = Outward::join('companies as company_1', 'outwards.company_id', '=', 'company_1.id')
            ->join('outward_items', 'outward_items.outward_id', '=', 'outwards.id')
            ->join('categories', 'outward_items.category_id', '=', 'categories.id')
            ->join('companies as company_2', 'outwards.supplier_id', '=', 'company_2.id')
            ->orderBy('outwards.id', 'desc')
            ->whereBetween('outwards.date', [$filterTodate, $filterFromdate])
            ->select(
                'outwards.date',
                'outwards.outward_number',
                'outwards.so_number',
                'company_1.company_name',
                'company_2.virtual_store',
                'outwards.status',
                'categories.name',
                DB::raw('SUM(outward_items.weight) as total_qty')
            )
            ->groupBy(
                'outwards.id',
                'outwards.outward_number',
                'outwards.status',
                'outwards.date',
                'categories.name',
                'outwards.total_weight',
                'company_1.company_name',
                'company_2.virtual_store',
                'outwards.so_number',
            );
        if ($filterCompany != 'all') {
            $query->where('outwards.company_id', $filterCompany);
        }

        if ($filterCategory != 'all') {
            $query->where('categories.id', $filterCategory);
        }


        $filteredDatas = $query->get();
        // dd($filteredDatas);
        $data = [];
        foreach ($filteredDatas as $filteredData) {
            // $labour_charge = $filteredData->loading_charges + $filteredData->additional_charges + $filteredData->freight;
            $tempData = [
                // 'date' => $filteredData->date,
                'date' => date('m-d-Y', strtotime($filteredData->date)),
                // 'outward_number' => $filteredData->outward_number,
                'outward_number' => $filteredData->outward_number,
                'so_number' => $filteredData->so_number,
                'company_name' => $filteredData->company_name,
                'virtual_store' => $filteredData->virtual_store,
                'category_name' => $filteredData->name,
                'total_quantity' => $filteredData->total_qty,
                'status' => $filteredData->status,
            ];
            $data[] = $tempData;
        }

        return response()->json($data);
    }




    // Stock Reports
    public function stock_report()
    {


        $Categorys = Category::all();
        $SubCategorys = SubCategory::all();
        $virtual_store = Company::where('type', 'supplier')->get();
        return view('reports.stock_report', compact('SubCategorys', 'Categorys', 'virtual_store'));
    }

    public function get_stock_report(Request $request)
    {

        // dd($request);

        $filterCategory = $request->filterCategory;
        $filtersubcategory = $request->filtersubcategory;
        $filterVirtualStore = $request->filterVirtualStore;


        $query = StockItem::join('categories', 'stock_items.category_id', '=', 'categories.id')
            ->join('subcategories', 'stock_items.sub_category_id', '=', 'subcategories.id')
            ->join('companies', 'stock_items.supplier_id', '=', 'companies.id')
            // ->whereBetween('stock_items.created_at', [$filterTodate, $filterFromdate])
            ->orderBy('stock_items.id', 'desc')
            ->select('categories.*', 'companies.*', 'subcategories.*', 'stock_items.*', 'stock_items.weight as w_weight',);



        if ($filterCategory != 'all') {
            $query->where('categories.id', $filterCategory);
        }

        if ($filtersubcategory != 'all') {
            $query->where('subcategories.id', $filtersubcategory);
        }

        if ($filterVirtualStore != 'all') {
            $query->where('stock_items.supplier_id', $filterVirtualStore);
        }

        $filteredDatas = $query->get();
        // dd($filteredDatas);
        $data = [];
        foreach ($filteredDatas as $filteredData) {
            $tempData = [
                'name' => $filteredData->name,
                'sub_category' => $filteredData->sub_category,
                'total_quantity' => $filteredData->w_weight,
                'virtual_store' => $filteredData->virtual_store,

            ];
            $data[] = $tempData;
        }

        return response()->json($data);
    }



    // Inward Reports
    public function inward_report()
    {

        $companys = Company::where('type', 'supplier')->get();
        $Categorys = Category::all();

        return view('reports.inward_report', compact('companys', 'Categorys'));
    }

    public function get_inward_report(Request $request)
    {
        // dd($request);
        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        $filterCompany = $request->filterCompany;
        $filterCategory = $request->filterCategory;

        $query = Inward::join('companies', 'inwards.supplier_id', '=', 'companies.id')
            ->join('inward_items', 'inwards.id', '=', 'inward_items.inward_id')
            ->join('categories', 'inward_items.category_id', '=', 'categories.id')
            ->whereBetween('inwards.date', [$filterTodate, $filterFromdate])
            ->orderBy('inwards.id', 'desc')
            ->select(
                'inwards.id',
                'inwards.date',
                'inwards.inward_number',
                'inwards.status',
                'companies.company_name',
                'categories.name as category_name',
                'companies.virtual_store',
                DB::raw('SUM(inward_items.weight) as total_quantity')

            )
            ->groupBy(
                'inwards.id',
                'inwards.date',
                'inwards.inward_number',
                'companies.company_name',
                'categories.name',
                'companies.virtual_store',
                'inwards.status',

            );


        if ($filterCompany != 'all') {
            $query->where('companies.id', $filterCompany);
        }



        if ($filterCategory != 'all') {
            $query->where('categories.id', $filterCategory);
        }

        $filteredDatas = $query->get();
        // dd($filteredDatas);

        $data = [];
        foreach ($filteredDatas as $filteredData) {

            $tempData = [
                'date' => date('m-d-Y', strtotime($filteredData->date)),
                'inward_number' => $filteredData->inward_number,
                'company_name' => $filteredData->company_name,
                'category_name' => $filteredData->category_name,
                'virtual_store' => $filteredData->virtual_store,
                'total_quantity' => $filteredData->total_quantity,
                'status' => $filteredData->status,
            ];
            $data[] = $tempData;
        }

        return response()->json($data);
    }



    // Top Selling Report

    public function top_selling_report()
    {

        return view('reports.top_selling_report');
    }


    public function get_top_selling_report(Request $request)
    {
        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;


        $query = Outward::join('companies', 'outwards.company_id', '=', 'companies.id')
            ->join('outward_items', 'outward_items.outward_id', '=', 'outwards.id')
            ->join('categories', 'outward_items.category_id', '=', 'categories.id')
            ->join('subcategories', 'outward_items.sub_category_id', '=', 'subcategories.id')
            // ->orderBy('outwards.id', 'desc')
            ->whereBetween('outwards.date', [$filterTodate, $filterFromdate])
            ->select(
                DB::raw('SUM(outward_items.weight) as total_weight'),
                DB::raw('SUM(outward_items.piece) as total_piece'),
                DB::raw('COUNT(DISTINCT outward_items.outward_id) as total_unique_outwards'),  // Count of distinct outward_id
                'categories.name as categories_name',
                // 'companies.company_name as company_name',
                'subcategories.sub_category as subcategories_name',
                DB::raw('COUNT(categories.id) as category_count')
            )
            ->groupBy('categories.name', 'subcategories.sub_category')
            ->orderBy('total_weight', 'desc');
        // ->orderBy('outward_items.weight', 'desc');
        // ->orderBy('outward_items.weight', 'asc');



        $quotation_data = $query->get();
        // dd($quotation_data);
        $data = [];
        foreach ($quotation_data as $quotation) {
            $tempData = [
                'categories_name' => $quotation->categories_name,
                'subcategories_name' => $quotation->subcategories_name,
                'weight' => $quotation->total_weight,
                'piece' => $quotation->total_piece,
                'total_frequency' => $quotation->total_unique_outwards,
            ];
            $data[] = $tempData;
        }

        return response()->json($data);
    }


    public function quotation_execution_report()
    {
        return view('reports.quotation_execution_report');
    }


    public function get_quotation_execution_report(Request $request)
    {
        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;

        $query = Quotation::join('companies', 'quotations.company_id', '=', 'companies.id')
            ->whereBetween('quotations.created_at', [$filterTodate, $filterFromdate])
            ->select('companies.*', 'quotations.*', 'quotations.id as q_id');


        $quotation = Quotation::whereBetween('created_at', [$filterTodate, $filterFromdate])
            ->count();

        $salesGeneratedCount = Quotation::where('status', 'sales generated')
            ->whereBetween('created_at', [$filterTodate, $filterFromdate])
            ->count();

        $value_1 = ($quotation / $salesGeneratedCount);
        $execution_value = ($value_1 / 100);

        $quotation_data = $query->get();



        $data = [];

        $data['execution_value'] = $execution_value;

        foreach ($quotation_data as $quotation) {
            $tempData = [
                'company_name' => $quotation->company_name,
                'quantity' => $quotation->total_weight,
                'amount' => $quotation->grand_total,
                'created_at' => date('m-d-Y', strtotime($quotation->quotation_date)),
                'status' => $quotation->status,
            ];
            $data['quotations'][] = $tempData;
        }
        // dd($data);
        return response()->json($data);
    }



    public function stock_transaction_report()
    {

        $category = Category::all();
        $sub_category = SubCategory::all();
        $warehouse = WareHouseModel::all();
        $stock_transaction = Transaction::all();

        $data = [
            'category' => $category,
            'sub_category' => $sub_category,
            'warehouse' => $warehouse,
            'stock_transaction' => $stock_transaction,
        ];
        return view('reports.stocktransaction_report')->with($data);
    }


    public function get_stock_transaction_report(Request $request)
    {

        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        $filterCategory = $request->filterCategory;
        $filtersubCategory = $request->filtersubCategory;
        $filterType = $request->filterType;
        $filterWarehouse = $request->filterWarehouse;
        $filterLength = $request->filterlength;

        $query = Transaction::join('categories', 'categories.id', '=', 'stock_transactions.category_id')
            ->join('subcategories', 'stock_transactions.subcategory_id', '=', 'subcategories.id')
            ->join('warehouse', 'stock_transactions.warehouse_id', '=', 'warehouse.id')
            ->join('users', 'stock_transactions.user_id', '=', 'users.id')
            ->orderBy('categories.id', 'desc')
            ->whereBetween(DB::raw('DATE(stock_transactions.created_at)'), [$filterTodate, $filterFromdate])
            ->select(
                'stock_transactions.id as transaction_id',
                DB::raw('MAX(categories.id) as category_id'),
                DB::raw('MAX(categories.name) as category_name'),
                DB::raw('MAX(subcategories.id) as sub_category_id'),
                DB::raw('MAX(subcategories.sub_category) as sub_category_name'),
                DB::raw('MAX(warehouse.id) as warehouse_id'),
                DB::raw('MAX(warehouse.warehouse_title) as warehouse_title'),
                DB::raw('MAX(stock_transactions.pcs) as stock_transactions_pcs'),
                DB::raw('MAX(stock_transactions.length) as stock_transactions_length'),
                DB::raw('MAX(stock_transactions.operation) as stock_transactions_operation'),
                DB::raw('MAX(stock_transactions.created_at) as created_at'),
                DB::raw('MAX(stock_transactions.type) as type'),
                DB::raw('MAX(stock_transactions.pcs) as pcs'),
                DB::raw('MAX(stock_transactions.length) as length'),
                DB::raw('MAX(stock_transactions.operation) as operation'),
                DB::raw('MAX(users.name) as user_name'),
                DB::raw('MAX(stock_transactions.ref_id) as ref_id'),
            )
            ->groupBy('stock_transactions.id');

        if ($filterCategory != 'all') {
            $query->where('stock_transactions.category_id', $filterCategory);
        }

        if ($filtersubCategory != 'all') {
            $query->where('stock_transactions.subcategory_id', $filtersubCategory);
        }
        if ($filterLength) {
            $query->where('stock_transactions.length', $filterLength);
        }

        if ($filterType != 'all') {
            $query->where('stock_transactions.type', $filterType);
        }

        if ($filterWarehouse != 'all') {
            $query->where('stock_transactions.warehouse_id', $filterWarehouse);
        }

        $filteredDatas = $query->get();
        $data = [];
        foreach ($filteredDatas as $filteredData) {
            $created_at = $filteredData->created_at;
            $date = new DateTime($created_at);
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $istDate = $date->format('m-d-Y H:i:s');

            $tempData = [
                'ref_id' => $filteredData->ref_id,
                'category' => $filteredData->category_name,
                'sub_category' => $filteredData->sub_category_name,
                'warehouse' => $filteredData->warehouse_title,
                'created_at' => $istDate,
                'type' => $filteredData->type,
                'pcs' => $filteredData->pcs,
                'length' => $filteredData->length,
                'operation' => $filteredData->operation,
                'user' => $filteredData->user_name,
            ];
            $data[] = $tempData;
        }

        return response()->json($data);
    }



    public function ageing_report()
    {
        $category = Category::all();
        $warehouse = WareHouseModel::all();
        return view('reports.ageing_report', compact('category', 'warehouse'));
    }

    public function get_ageing_report(Request $request)
    {
        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        $filterCategory = $request->filterCategory;
        $filtersubcategory = $request->filtersubCategory;
        $filterLength = $request->filterlength;
        $filterWareHouse = $request->filterWarehouse;
        $filterAge = $request->filterage;

        $query = Ageing::join('categories', 'ageings.category_id', '=', 'categories.id')
            ->join('subcategories', 'ageings.subcategory_id', '=', 'subcategories.id')
            ->join('warehouse', 'ageings.warehouse_id', '=', 'warehouse.id')
            ->orderBy('ageings.id', 'desc')
            ->whereBetween(DB::raw('DATE(ageings.created_at)'), [$filterTodate, $filterFromdate])
            ->select(
                'categories.name as category',
                'subcategories.sub_category as subcategory',
                'ageings.category_id',
                'ageings.subcategory_id',
                'ageings.length',
                'ageings.warehouse_id',
                'warehouse.warehouse_title as warehouse',
                DB::raw('SUM(ageings.balance) as total_quantity'),
                DB::raw('AVG(ageings.age) as average_age'),
                DB::raw('CASE 
                    WHEN AVG(ageings.age) BETWEEN 0 AND 30 THEN "0-30"
                    WHEN AVG(ageings.age) BETWEEN 30 AND 60 THEN "30-60"
                    WHEN AVG(ageings.age) BETWEEN 60 AND 90 THEN "60-90"
                    ELSE "90+" 
                 END as age_group')
            )
            ->groupBy(
                'categories.name',
                'subcategories.sub_category',
                'ageings.category_id',
                'ageings.subcategory_id',
                'ageings.length',
                'ageings.warehouse_id',
                'warehouse.warehouse_title'
            );

        if ($filterCategory != 'all') {
            $query->where('ageings.category_id', $filterCategory);
        }

        if ($filtersubcategory != 'all') {
            $query->where('ageings.subcategory_id', $filtersubcategory);
        }

        if ($filterLength) {
            $query->where('ageings.length', $filterLength);
        }

        if ($filterWareHouse != 'all') {
            $query->where('ageings.warehouse_id', $filterWareHouse);
        }

        if ($filterAge != 'all') {
            $query->having('age_group', $filterAge);
        }

        $filteredDatas = $query->get();

        $data = [];
        foreach ($filteredDatas as $filteredData) {
            $tempData = [
                'category' => $filteredData->category,
                'sub_category' => $filteredData->subcategory,
                'length' => $filteredData->length,
                'warehouse' => $filteredData->warehouse,
                'total_qty' => $filteredData->total_quantity,
                'average_age' => $filteredData->average_age,
                'age_group' => $filteredData->age_group,
            ];
            // dd($data);
            $data[] = $tempData;
        }

        return response()->json($data);
    }

    public function lifo_report()
    {
        // dd(1);
        return view('reports.lifo_report');
    }

    public function calculateLifoReport()
    {

        $purchases = InventoryTransaction::where('transaction_type', 'purchase')
            ->orderBy('transaction_date', 'desc')
            ->get();


        $issues = InventoryTransaction::where('transaction_type', 'issue')
            ->orderBy('transaction_date')
            ->get();

        $inventoryStack = [];
        $costOfGoodsSold = 0;


        foreach ($purchases as $purchase) {
            $inventoryStack[] = [
                'quantity' => $purchase->quantity,
                'unit_price' => $purchase->unit_price,
            ];
        }

        foreach ($issues as $issue) {
            $issueQuantity = $issue->quantity * -1;

            while ($issueQuantity > 0) {
                if (empty($inventoryStack)) {
                    throw new \Exception('Not enough inventory to fulfill the issue.');
                }

                $lastPurchase = array_pop($inventoryStack);

                if ($lastPurchase['quantity'] > $issueQuantity) {

                    $costOfGoodsSold += $issueQuantity * $lastPurchase['unit_price'];
                    $lastPurchase['quantity'] -= $issueQuantity;
                    array_push($inventoryStack, $lastPurchase);
                    $issueQuantity = 0;
                } else {

                    $costOfGoodsSold += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                    $issueQuantity -= $lastPurchase['quantity'];
                }
            }
        }

        return $costOfGoodsSold;
    }

    // public function calculateLIFO()
    // {
    //     $purchases = InventoryTransaction::where('transaction_type', 'purchase')
    //         ->orderBy('transaction_date', 'asc') 
    //         ->get();

    //     $issues = InventoryTransaction::where('transaction_type', 'issue')
    //         ->orderBy('transaction_date', 'asc')
    //         ->get();

    //     $inventoryStack = []; 
    //     $transactionLogs = []; 
    //     $totalQuantity = 0;
    //     $totalValue = 0;


    //     foreach ($purchases as $purchase) {
    //         $inventoryStack[] = [
    //             'quantity' => $purchase->quantity,
    //             'unit_price' => $purchase->unit_price,
    //             'total_value' => $purchase->quantity * $purchase->unit_price,
    //             'transaction_date' => $purchase->transaction_date,
    //         ];

    //         $totalQuantity += $purchase->quantity;
    //         $totalValue += $purchase->quantity * $purchase->unit_price;


    //         $transactionLogs[] = [
    //             'transaction_type' => 'Purchase',
    //             'quantity' => $purchase->quantity,
    //             'unit_price' => $purchase->unit_price,
    //             'transaction_date' => $purchase->transaction_date,
    //             'balance_qty' => $totalQuantity,
    //             'balance_value' => $totalValue,
    //         ];
    //     }


    //     foreach ($issues as $issue) {
    //         $issueQty = abs($issue->quantity); 


    //         $logEntry = [
    //             'transaction_type' => 'Issue',
    //             'quantity' => $issueQty,
    //             'transaction_date' => $issue->transaction_date,
    //             'details' => []
    //         ];

    //         while ($issueQty > 0 && !empty($inventoryStack)) {

    //             $lastPurchase = array_pop($inventoryStack);

    //             if ($lastPurchase['quantity'] > $issueQty) {

    //                 $usedQty = $issueQty;
    //                 $remainingQty = $lastPurchase['quantity'] - $issueQty;


    //                 $totalQuantity -= $usedQty;
    //                 $totalValue -= $usedQty * $lastPurchase['unit_price'];


    //                 $inventoryStack[] = [
    //                     'quantity' => $remainingQty,
    //                     'unit_price' => $lastPurchase['unit_price'],
    //                     'total_value' => $remainingQty * $lastPurchase['unit_price'],
    //                     'transaction_date' => $lastPurchase['transaction_date'],
    //                 ];

    //                 $logEntry['details'][] = [
    //                     'used_qty' => $usedQty,
    //                     'unit_price' => $lastPurchase['unit_price'],
    //                     'remaining_qty' => $remainingQty,
    //                     'remaining_value' => $remainingQty * $lastPurchase['unit_price']
    //                 ];

    //                 $issueQty = 0; 
    //             } else {

    //                 $usedQty = $lastPurchase['quantity'];


    //                 $totalQuantity -= $usedQty;
    //                 $totalValue -= $usedQty * $lastPurchase['unit_price'];

    //                 $logEntry['details'][] = [
    //                     'used_qty' => $usedQty,
    //                     'unit_price' => $lastPurchase['unit_price'],
    //                     'remaining_qty' => 0,
    //                     'remaining_value' => 0
    //                 ];

    //                 $issueQty -= $usedQty; 
    //             }
    //         }


    //         $transactionLogs[] = [
    //             'transaction_type' => 'Issue',
    //             'quantity' => $logEntry['quantity'],
    //             'transaction_date' => $logEntry['transaction_date'],
    //             'balance_qty' => $totalQuantity,
    //             'balance_value' => $totalValue,
    //             'details' => $logEntry['details'] 
    //         ];
    //     }


    //     return response()->json([
    //         'transaction_logs' => $transactionLogs,
    //         'final_balance_qty' => $totalQuantity,
    //         'final_balance_value' => $totalValue
    //     ]);
    // }

    public function calculateLIFO()
    {
        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

        $inventoryStack = [];  // LIFO Stack to hold inventory
        $transactionLogs = []; // To log each transaction for the report
        $totalQuantity = 0;
        $totalValue = 0;
        $totalProfitLoss = 0;

        foreach ($transactions as $transaction) {
            if (strtolower($transaction->transaction_type) === 'purchase') {
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
                $transactionLogs[] = [
                    'transaction_type' => 'Purchase',
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue, // This should now correctly reflect after every purchase
                    'cost_of_goods_sold' => 0,
                    'profit_loss' => 0,
                ];
            } elseif (strtolower($transaction->transaction_type) === 'sell') {
                $sellQty = abs($transaction->quantity); // Quantity sold is positive
                $costOfGoodsSold = 0; // Track cost for the sale
                $logEntry = [
                    'transaction_type' => 'Sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $transaction->unit_price,
                    'details' => []
                ];

                // LIFO logic - Sell the most recent purchase first
                while ($sellQty > 0 && !empty($inventoryStack)) {
                    $lastPurchase = array_pop($inventoryStack); // Get the last batch

                    if ($lastPurchase['quantity'] >= $sellQty) {
                        // If the last purchase batch can fulfill the sell quantity
                        $costOfGoodsSold += $sellQty * $lastPurchase['unit_price'];
                        $totalQuantity -= $sellQty;
                        $totalValue -= $sellQty * $lastPurchase['unit_price'];

                        // Calculate remaining quantity and value for this batch
                        $remainingQty = $lastPurchase['quantity'] - $sellQty;
                        $remainingValue = $remainingQty * $lastPurchase['unit_price'];

                        // Push remaining stock back into the stack if there is any left
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
                        $sellQty = 0; // Sale fully fulfilled

                    } else {
                        // If the last purchase batch can't fulfill the sell quantity
                        $costOfGoodsSold += $lastPurchase['quantity'] * $lastPurchase['unit_price'];
                        $sellQty -= $lastPurchase['quantity'];
                        $totalQuantity -= $lastPurchase['quantity'];
                        $totalValue -= $lastPurchase['quantity'] * $lastPurchase['unit_price'];

                        // No remaining quantity from this batch
                        $logEntry['details'][] = [
                            'used_qty' => $lastPurchase['quantity'],
                            'unit_price' => $lastPurchase['unit_price'],
                            'remaining_qty' => 0,
                            'remaining_value' => 0,
                        ];
                    }
                }

                // Calculate profit/loss
                $totalSaleValue = abs($transaction->quantity) * $transaction->unit_price;
                $profitLoss = $totalSaleValue - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;

                // Log the sell transaction
                $transactionLogs[] = [
                    'transaction_type' => 'Sell',
                    'quantity' => abs($transaction->quantity),
                    'selling_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue, // Corrected balance value after sale
                    'cost_of_goods_sold' => $costOfGoodsSold,
                    'profit_loss' => $profitLoss,
                    'total_profit_loss' => $totalProfitLoss,
                    'details' => $logEntry['details'],
                ];
            }
        }

        return view('reports.lifo_report', [
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue, // Final corrected balance value
            'final_profit_loss' => $totalProfitLoss,
        ]);
    }







    public function calculateFIFO()
    {

        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

        $inventoryQueue = [];
        $transactionLogs = [];
        $totalQuantity = 0;
        $totalValue = 0;
        $totalProfitLoss = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type === 'purchase') {

                $inventoryQueue[] = [
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
                    'balance_value' => $totalValue
                ];
            } elseif ($transaction->transaction_type === 'sell') {

                $sellQty = abs($transaction->quantity);
                $sellingPrice = $transaction->unit_price;
                $logEntry = [
                    'transaction_type' => 'sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $sellingPrice,
                    'details' => []
                ];

                $costOfGoodsSold = 0;


                while ($sellQty > 0 && !empty($inventoryQueue)) {
                    $firstPurchase = array_shift($inventoryQueue);

                    if ($firstPurchase['quantity'] >= $sellQty) {

                        $remainingQty = $firstPurchase['quantity'] - $sellQty;


                        $costOfGoodsSold += $sellQty * $firstPurchase['unit_price'];
                        $totalValue -= $sellQty * $firstPurchase['unit_price'];
                        $totalQuantity -= $sellQty;

                        if ($remainingQty > 0) {

                            $inventoryQueue[] = [
                                'quantity' => $remainingQty,
                                'unit_price' => $firstPurchase['unit_price'],
                                'transaction_date' => $firstPurchase['transaction_date'],
                            ];
                        }


                        $logEntry['details'][] = [
                            'used_qty' => $sellQty,
                            'unit_price' => $firstPurchase['unit_price'],
                            'remaining_qty' => $remainingQty,
                            'remaining_value' => $remainingQty * $firstPurchase['unit_price'],
                        ];

                        $sellQty = 0;
                    } else {

                        $usedQty = $firstPurchase['quantity'];
                        $costOfGoodsSold += $usedQty * $firstPurchase['unit_price'];
                        $totalValue -= $usedQty * $firstPurchase['unit_price'];
                        $totalQuantity -= $usedQty;

                        $logEntry['details'][] = [
                            'used_qty' => $usedQty,
                            'unit_price' => $firstPurchase['unit_price'],
                            'remaining_qty' => 0,
                            'remaining_value' => 0,
                        ];

                        $sellQty -= $usedQty;
                    }
                }


                $totalSaleValue = $transaction->quantity * $sellingPrice;
                $profitLoss = $totalSaleValue - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;


                $transactionLogs[] = [
                    'transaction_type' => 'sell',
                    'quantity' => $logEntry['quantity'],
                    'transaction_date' => $logEntry['transaction_date'],
                    'selling_price' => $logEntry['selling_price'],
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'cost_of_goods_sold' => $costOfGoodsSold,
                    'profit_loss' => $profitLoss,
                    'total_profit_loss' => $totalProfitLoss,
                    'details' => $logEntry['details']
                ];
            }
        }


        return response()->json([
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue,
            'final_profit_loss' => $totalProfitLoss
        ]);
    }


    public function calculateAverageCost()
    {
        $transactions = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

        $totalQuantity = 0;
        $totalValue = 0;
        $totalProfitLoss = 0;
        $transactionLogs = [];

        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type === 'purchase') {
                $totalQuantity += $transaction->quantity;
                $totalValue += $transaction->quantity * $transaction->unit_price;
                $averageCost = $totalValue / $totalQuantity;

                $transactionLogs[] = [
                    'transaction_type' => 'Purchase',
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'average_cost' => $averageCost
                ];
            } elseif ($transaction->transaction_type === 'sell') {
                $sellQty = abs($transaction->quantity);
                $sellingPrice = $transaction->unit_price;
                $costOfGoodsSold = $sellQty * ($totalValue / $totalQuantity);

                $totalQuantity -= $sellQty;
                $totalValue -= $costOfGoodsSold;

                $profitLoss = ($transaction->quantity * $sellingPrice) - $costOfGoodsSold;
                $totalProfitLoss += $profitLoss;

                $transactionLogs[] = [
                    'transaction_type' => 'Sell',
                    'quantity' => $sellQty,
                    'transaction_date' => $transaction->transaction_date,
                    'selling_price' => $sellingPrice,
                    'balance_qty' => $totalQuantity,
                    'balance_value' => $totalValue,
                    'cost_of_goods_sold' => $costOfGoodsSold,
                    'profit_loss' => $profitLoss,
                    'total_profit_loss' => $totalProfitLoss
                ];
            }
        }

        return response()->json([
            'transaction_logs' => $transactionLogs,
            'final_balance_qty' => $totalQuantity,
            'final_balance_value' => $totalValue,
            'final_profit_loss' => $totalProfitLoss
        ]);
    }


    public function inventory_report()
    {
        $category = Category::all();
        return view('reports.inventory_report', compact('category'));
    }


    public function get_inventory_report(Request $request)
    {

        $filterCategory = $request->filterCategory;
        // dd( $filterCategory);

        if($filterCategory != 'all'){

            $filteredPOTotals = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->where('po_items.item_category', $filterCategory)
            ->select('po_items.item_category', 'categories.id as category_id', 'categories.name as category_name', DB::raw('SUM(po_items.po_dispatch_rest_qty) as total_quantity'))
            ->groupBy('po_items.item_category', 'categories.name','categories.id')
            ->get();


        $filteredSOTotals = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->where('so_items.item_category', $filterCategory)
            ->select(
                'so_items.item_category',
                'categories.name as category_name',
                'categories.id as category_id',
                DB::raw('SUM(so_items.so_dispatch_rest_qty) as total_quantity')
            )
            ->groupBy('so_items.item_category', 'categories.name', 'categories.id')
            ->get();
        }else{
            $filteredPOTotals = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->select('po_items.item_category',  'categories.id as category_id', 'categories.name as category_name', DB::raw('SUM(po_items.po_dispatch_rest_qty) as total_quantity'))
            ->groupBy('po_items.item_category', 'categories.name', 'categories.id')
            ->get();


        $filteredSOTotals = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->select(
                'so_items.item_category',
                'categories.name as category_name',
                'categories.id as category_id',
                DB::raw('SUM(so_items.so_dispatch_rest_qty) as total_quantity')
            )
            ->groupBy('so_items.item_category', 'categories.name', 'categories.id')
            ->get();
        }

     


        $data = [
            'filteredPOTotal' => $filteredPOTotals,
            'filteredSOTotal' => $filteredSOTotals,
        ];

        return response()->json($data);
    }

    public function dispatch_report()
    {
       $company =  Company::all();
       $category = Category::all();

        return view('reports.dispatch',compact('company','category'));
    }

    public function get_dispatch_report(Request $request)
    {
        $filterTodate = $request->filterTodate;
        $filterFromdate = $request->filterFromdate;
        $filterItem_name = $request ->filterItem_name;
        $filterCompany = $request->filterCompany;

        $query =  Dispatch::leftjoin('so_items', 'dispatches.so_item_id', '=', 'so_items.id')
        ->leftjoin('po_items', 'dispatches.po_item_id', '=', 'po_items.id')
        ->leftjoin('companies as po_company', 'dispatches.po_company_id', '=', 'po_company.id')
        ->leftjoin('companies as so_company', 'dispatches.so_company_id', '=', 'so_company.id')
        ->leftjoin('sales_orders', 'dispatches.so_id', '=', 'sales_orders.id')
        ->leftjoin('purchase_orders', 'dispatches.po_id', '=', 'purchase_orders.id')
        ->leftjoin('categories', 'dispatches.category_id', '=', 'categories.id')
        ->leftjoin('subcategories', 'dispatches.subcategory_id', '=', 'subcategories.id')
        ->select(
            'dispatches.*',
            'dispatches.created_at',
            'dispatches.id as dispatch_id',
            'so_items.so_dispatch_rest_qty',
            'po_items.po_dispatch_rest_qty',
            'po_company.company_name as po_company',
            'so_company.company_name as so_company',
            'sales_orders.so_number',
            'purchase_orders.id as po_id',
            'sales_orders.id as so_id',
            'purchase_orders.document_number as po_number',
            'sales_orders.date as so_date',
            'purchase_orders.date as po_date',
            'categories.name as category_name',
            'subcategories.sub_category as sub_category_name',
            'po_items.po_item_no',
            'so_items.so_item_no',
            'po_items.qty as po_qty',
            'so_items.qty as so_qty',
            'dispatches.id as dispatch_id',
            'dispatches.created_at as dispatch_date',

        )
            ->whereBetween('dispatches.created_at', [$filterTodate, $filterFromdate]);

            if ($filterItem_name && $filterItem_name != 'all') {
                $query->where('dispatches.category_id', $filterItem_name);
            }
            if ($filterCompany && $filterCompany != 'all') {
                $query->where(function ($q) use ($filterCompany) {
                    $q->where('dispatches.po_company_id', $filterCompany)
                      ->orWhere('dispatches.so_company_id', $filterCompany);
                });
            }
            
            $filteredData = $query->get();
            $data = [];
            foreach ($filteredData as $filteredData) {
                $tempData = [
                    'po_company'=>$filteredData->po_company,
                    'so_company' => $filteredData->so_company,
                    'created_at' => date('d-M-Y', strtotime($filteredData->created_at)),
                    'category_name' => $filteredData->category_name, 
                    'sub_category_name' => $filteredData->sub_category_name, 
                    'dispatched_quantity' => $filteredData->dispatched_quantity, 
                    'vehicle_number' => $filteredData->vehicle_number, 
                    'po_item_no' => $filteredData->po_item_no, 
                    'dispatch_total' => $filteredData->dispatch_total, 
                    'so_item_no' => $filteredData->so_item_no, 
                    'dispatch_so_total' => $filteredData->dispatch_so_total, 
                    'dispatch_id' => $filteredData->dispatch_id, 
                ];
                $data[] = $tempData;
            }
            return response()->json($data);
    }
}
