<?php

namespace App\Http\Controllers;

use App\Livewire\Warehouse;
use App\Models\Ageing;
use App\Models\Category;
use App\Models\Company;
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



        $query = PurchaseOrder::join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
            ->join('categories', 'purchase_orders.category', '=', 'categories.id')
            ->leftJoin('inwards', 'purchase_orders.document_number', '=', 'inwards.po_document_number')
            ->leftJoin('inward_items', 'inwards.id', '=', 'inward_items.inward_id')
            ->select(
                'purchase_orders.id as po_id',
                'purchase_orders.date as po_date',
                'purchase_orders.due_date as due_date',
                'purchase_orders.document_number',
                'purchase_orders.quantity',
                'companies.company_name',
                'categories.name',
                DB::raw('(purchase_orders.quantity - SUM(inward_items.weight)) as rest_quantity') // Calculation for rest_quantity
            )
            ->where('inwards.status', 'Approved')
            ->whereBetween('purchase_orders.date', [$filterTodate, $filterFromdate])
            ->groupBy(
                'purchase_orders.id',
                'purchase_orders.date',
                'purchase_orders.due_date',
                'purchase_orders.document_number',
                'purchase_orders.quantity',
                'companies.company_name',
                'categories.name'
            )
            ->orderBy('purchase_orders.document_number', 'desc');


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
                'po_id' => $filteredData->po_id,
                'po_document_number' => $filteredData->document_number,
                'date' => date('m-d-Y', strtotime($filteredData->po_date)),
                'due_date' => date('m-d-Y', strtotime($filteredData->due_date)),
                'company_name' => $filteredData->company_name,
                'quantity' => $filteredData->quantity,
                'rest_quantity' => $filteredData->rest_quantity ?? 0,
                // 'document_number' => '<a href="purchase-edit/' . $filteredData->id . '  ">' . $filteredData->document_number . '</a>',
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
        // dd($filterTodate, $filterFromdate, $filterCompany, $filterCategory);


        $query = SalesOrder::join('companies as company_1', 'company_1.id', '=', 'sales_orders.company_id')
            ->leftJoin('outwards', 'outwards.id', '=', 'sales_orders.so_number')
            ->leftJoin('companies as company_2', 'company_2.id', '=', 'outwards.supplier_id')
            ->join('so_items', 'so_items.sale_id', '=', 'sales_orders.id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->orderBy('sales_orders.id', 'desc')
            ->whereBetween('sales_orders.date', [$filterTodate, $filterFromdate])
            ->select(
                'sales_orders.id as sales_id',
                'categories.name as category_name',
                'company_1.company_name as company_name',
                'company_2.virtual_store as virtual_store',
                'sales_orders.so_number as so_number',
                'sales_orders.total_quantity as total_quantity',
                'sales_orders.date as date',
            )
            ->groupBy(

                'sales_orders.id',
                'categories.name',
                'company_1.company_name',
                'company_2.virtual_store',
                'sales_orders.so_number',
                'sales_orders.total_quantity',
                'sales_orders.date',
            );

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
                'date' => date('m-d-Y', strtotime($filteredData->date)),
                'so_number' => $filteredData->so_number,
                'company_name' => $filteredData->company_name,
                'rest_qty' => $filteredData->total_quantity,
                'virtual_store' => $filteredData->virtual_store ?? 'N/A',
                'total_quantity' => $filteredData->total_quantity,
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
            ->select('categories.*', 'companies.*', 'subcategories.*', 'stock_items.*', 'stock_items.weight as w_weight', );



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
}
