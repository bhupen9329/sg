<?php

namespace App\Http\Controllers;

use App\Models\Ageing;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Inward;
use App\Models\InwardItem;
use App\Models\PurchaseOrder;
use App\Models\StockItem;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\WareHouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InwardController extends Controller
{


    // function __construct()
    // {
    //     $this->middleware('permission:Inward-index', ['only' => ['index']]);
    //     $this->middleware('permission:Inward-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:Inward-view', ['only' => ['edit']]);
    //     $this->middleware('permission:Inward-edit', ['only' => ['update']]);
    //     $this->middleware('permission:Inward-approve', ['only' => ['approve']]);
    //     $this->middleware('permission:Inward-delete', ['only' => ['delete', '']]);
    // }

    public function index()
    {
        $inward_data = Inward::join('companies', 'inwards.supplier_id', '=', 'companies.id')
            ->leftJoin('inward_items', 'inwards.id', '=', 'inward_items.inward_id')
            ->select(
                'inwards.id as i_id',
                'inwards.inward_number',
                'inwards.total_weight',
                'inwards.date',
                'inwards.status',
                'companies.company_name',
            )
            ->groupBy(
                'inwards.id',
                'inwards.inward_number',
                'inwards.total_weight',
                'inwards.date',
                'inwards.status',
                'companies.company_name',
            )
            ->orderBy('inwards.id', 'desc')
            ->get();

        $warehouse = WareHouseModel::get();
        $companies = Company::get();
        $po_number = PurchaseOrder::select('document_number')->get();
        $seller_companies = Company::where('type', 'supplier')->get();
        // dd($po_number, $seller_companies);
        return view('inward.index', compact('inward_data', 'warehouse', 'companies', 'po_number', 'seller_companies'));
    }

    public function create(Request $request)
    {
        // dd($request);
        $year = date('Y');
        $company = Company::where('id', $request->company_id)->first();
        $warehouse = WareHouseModel::where('id', $request->warehouse_id)->first();
        // dd($warehouse);
        $item_category = Category::get();
        $company_setting = CompanySetting::first();

        $max_serial_number = Inward::orderBy('inward_number', 'desc')->first();
        if ($max_serial_number) {
            $max_serial_number = $max_serial_number->inward_number;
            $last_serial_number = substr($max_serial_number, -4);
            $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Default serial number when no records exist
            $next_serial_number = str_pad(1, 4, '0', STR_PAD_LEFT);
        }
        $inward_id = 'IN' . $year . $next_serial_number;
        $data = [
            'inward_id' => $inward_id,
            'inward_type' => $request->selected_type,
            'company' => $company,
            'category' => $item_category,
            'company_setting' => $company_setting,
            'warehouse' => $warehouse,
        ];
        // dd($data);
        return view('inward.create')->with($data);
    }
    public function Purchase_inward_create(Request $request)
    {
        // dd($request);
        $year = date('Y');
        // $po_data = PurchaseOrder::where('document_number', $request->po_number)->first();
        $seller_companies = Company::where('id', $request->seller_id)->first();
        // dd($warehouse);
        $item_category = Category::get();
        $company_setting = CompanySetting::first();
        // $max_serial_number = Inward::where('supplier_id', $request->company_id)->max('inward_number');
        // $last_serial_number = substr($max_serial_number, -4);
        // $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);

        $max_serial_number = Inward::orderBy('inward_number', 'desc')->first();
        if ($max_serial_number) {
            $max_serial_number = $max_serial_number->inward_number;
            $last_serial_number = substr($max_serial_number, -4);
            $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Default serial number when no records exist
            $next_serial_number = str_pad(1, 4, '0', STR_PAD_LEFT);
        }
        $inward_id = 'IN' . $year . $next_serial_number;
        $data = [
            'inward_id' => $inward_id,
            'inward_type' => $request->selected_type,
            'po_number' => $request->po_number,
            'category' => $item_category,
            'company_setting' => $company_setting,
            'seller_companies' => $seller_companies,
        ];
        // dd($data);
        return view('inward.create_purchase_inward')->with($data);
    }

    public function store(Request $request)
    {
        // dd($request);
        // dd($request->company_id);

        $inward = new Inward();
        $inward->inward_number = $request->inward_id;
        $inward->supplier_id = $request->seller_id;
        $inward->po_document_number = $request->po_number;
        $inward->date = $request->date;
        $inward->inw_remarks = $request->inw_remarks;
        $inward->total_weight = $request->total_weight;
        $inward->status = 'pending';

        $inward->save();
        $id = $inward->id;
        // dd($id);
        if ($id) {

            //SO Item Code
            for ($i = 0; $i < count($request->item_category); $i++) {

                // dd($request->uom_type);
                $inwardItem = new InwardItem();
                $inwardItem->category_id = $request->item_category[$i];
                $inwardItem->sub_category_id = $request->item_sub_category[$i];
                $inwardItem->weight = $request->weight[$i];
                $inwardItem->inward_id = $id;
                $inwardItem->save();
            }
            return redirect()->route('inward.index')->with('success', 'inward created Successfully');
        }
        // dd($po_data);
    }

    public function edit($id)
    {
        // dd($id);
        $inward_data = Inward::where('id', $id)->first();
        $company = Company::where('id', $inward_data->supplier_id)->first();
        $warehouse = WareHouseModel::where('id', $inward_data->warehouse_id)->first();
        $company_setting = CompanySetting::first();
        $inward_item = InwardItem::join('inwards', 'inward_items.inward_id', '=', 'inwards.id')
            ->join('subcategories', 'inward_items.sub_category_id', '=', 'subcategories.id')
            ->join('categories', 'inward_items.category_id', '=', 'categories.id')

            ->select(
                'inward_items.id as inward_item_id',
                'inward_items.inward_id',
                'inward_items.category_id',
                'inward_items.sub_category_id',
                'inward_items.weight as inward_weight',
                'categories.name as category_name',
                'categories.price as category_price',
                'subcategories.sub_category as subcategory_name',
            )
            ->where('inward_items.inward_id', $id)
            ->get();
        // dd($inward_item);

        $item_category = Category::get();

        $sub_category = [];

        foreach ($item_category as $categorys) {
            $subcategory = SubCategory::where('category_id', $categorys->id)->first();
            if ($subcategory !== null) {
                $sub_category[] = $subcategory;
            }
        }


        $count = InwardItem::where('inward_id', $id)->count();
        $data = [
            'inward_id' => $inward_data->inward_number,
            'inward_type' => $inward_data->type,
            'inward_data' => $inward_data,
            'inward_item' => $inward_item,
            'count' => $count,
            'company' => $company,
            'company_setting' => $company_setting,
            'category' => $item_category,
            'sub_category' => $sub_category,
            'warehouse' => $warehouse,
        ];
        // dd($data);
        return view('inward.edit_purchase_inward', )->with($data);
    }

    public function update(Request $request, $id)
    {

        // dd($request , $id);
        $data = [
            'inward_number' => $request->inward_id,
            'supplier_id' => $request->company_id,
            'date' => $request->date,
            'vehicle_number' => $request->vehicle_number,
            'warehouse_id' => $request->warehouse_id,
            'total_weight' => $request->total_weight,
            'godown_weight' => $request->godown_weight,
            'plant_weight' => $request->plant_weight,
            'type' => $request->type,
            'error_message' => $request->error_message,
            'crane_charge' => $request->crane_charge,
            'labour_charge' => $request->labour_charge,
            'kanta_charge' => $request->kanta_charge,
        ];
        // dd($data);
        Inward::where('id', $id)->update($data);
        InwardItem::where('inward_id', $id)->delete();
        if ($id) {
            //SO Item Code
            for ($i = 0; $i < count($request->item_category); $i++) {
                // dd($uom_type);
                $inwardItem = new InwardItem();
                $inwardItem->category_id = $request->item_category[$i];
                $inwardItem->sub_category_id = $request->item_sub_category[$i];
                $inwardItem->length = $request->length[$i];
                $inwardItem->piece = $request->piece[$i];
                $inwardItem->weight = $request->weight[$i];
                $inwardItem->inward_id = $id;
                $inwardItem->save();
            }
            return redirect()->route('inward.index')->with('update', 'inward Updated Successfully');
        }
    }

    public function show($id)
    {
        $inward_data = Inward::join('companies', 'inwards.supplier_id', '=', 'companies.id')
            ->select('companies.*', 'inwards.*', 'inwards.created_at as po_created_at', 'inwards.id as po_id')->where('inwards.id', $id)->first();
        // dd($po_data);
        return view('inward.show', compact('inward_data'));
    }
    public function approve($id)
    {
        $inwardData = Inward::join('inward_items', 'inwards.id', '=', 'inward_items.inward_id')
            ->where('inwards.id', $id)
            ->get();


        // dd($inwardData);
        foreach ($inwardData as $data) {
            $existingStockItem = StockItem::where([
                ['supplier_id', $data->supplier_id],
                ['category_id', $data->category_id],
                ['sub_category_id', $data->sub_category_id],
            ])->first();

            if ($existingStockItem) {
                // Update the existing stock item
                $existingStockItem->weight += $data->weight;
                $existingStockItem->save();
            } else {
                // Create a new stock item
                StockItem::create([
                    'supplier_id' => $data->supplier_id,
                    'category_id' => $data->category_id,
                    'sub_category_id' => $data->sub_category_id,
                    'weight' => $data->weight,
                ]);
            }

        }


        Inward::where('id', $id)->update(['status' => 'Approved']);
        return redirect()->route('inward.index')->with('approve', 'Inward approved Successfully.');
    }
    public function delete($id)
    {
        Inward::where('id', $id)->delete();
        InwardItem::where('inward_id', $id)->delete();
        return redirect()->route('inward.index')->with('delete', 'Inward Deleted Successfully');
    }

    public function get_sub_category(Request $request)
    {
        $category = Category::where('id', $request->item_id)->first();
        $sub_category = SubCategory::where('category_id', $request->item_id)->get();
        return response([
            'subcategory' => $sub_category,
            'category' => $category
        ]);
    }

    public function get_po_number_for_supplier(Request $request)
    {
        $po_number = PurchaseOrder::where('supplier_id', $request->supplier_id)->select('document_number')->get();
        return response(
            $po_number
        );
    }
    public function current_quantity_form_po(Request $request)
    {
        $item_id = $request->item_id;
        $sub_category_id = $request->item_sub_category;
        $seller_id = $request->seller_id;
        $po_document_number = $request->po_document_number;
        // dd($item_id, $sub_category_id, $seller_id);
        $data = PurchaseOrder::where('supplier_id', $seller_id)
            ->first();
        $inward_qty = Inward::where('po_document_number', $po_document_number)
            ->selectRaw('SUM(total_weight) as total_quantity')
            ->first();
        // dd($data);
        return response([
            'data' => $data,
            'inward_qty' => $inward_qty,
        ]);
    }

    public function change_credit_note_status(Request $request)
    {
        // dd($request);

        Inward::where('id', $request->inward_id)->update(['credit_note_status' => $request->credit_note_status]);
        return redirect()->route('inward.index')->with('Credit_note_status', 'Credit note status Updated Successfully');
        // $sub_category = SubCa
    }
}
