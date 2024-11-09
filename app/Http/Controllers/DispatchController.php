<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Company;
use App\Models\SalesOrder;
use App\Models\PoItem;
use App\Models\SoItem;
use App\Models\Category;
use App\Models\ConvRate;
use App\Models\SubCategory;
use App\Models\Dispatch;
use App\Models\FreightRate;

class DispatchController extends Controller
{


    public function index()
    {

        $disaptch_data = Dispatch::leftjoin('so_items', 'dispatches.so_item_id', '=', 'so_items.id')
            ->leftjoin('po_items', 'dispatches.po_item_id', '=', 'po_items.id')
            ->leftjoin('companies as po_company', 'dispatches.po_company_id', '=', 'po_company.id')
            ->leftjoin('companies as so_company', 'dispatches.so_company_id', '=', 'so_company.id')
            ->leftjoin('sales_orders', 'dispatches.so_id', '=', 'sales_orders.id')
            ->leftjoin('purchase_orders', 'dispatches.po_id', '=', 'purchase_orders.id')
            ->leftjoin('categories', 'dispatches.category_id', '=', 'categories.id')
            ->leftjoin('subcategories', 'dispatches.subcategory_id', '=', 'subcategories.id')
            ->select(
                'dispatches.*',
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
            ->get();
            // dd($disaptch_data);

        return view('dispatch.index', compact('disaptch_data'));
    }

    public function create()
    {
        $purchase_orders = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->get();
        // dd($purchase_orders);
        $sales_orders = SalesOrder::all();
        $companies_po = Company::where('type', 'supplier')->get();
        $companies_so = Company::where('type', 'buyer')->get();
     
        return view('dispatch.create', compact('purchase_orders', 'sales_orders', 'companies_po', 'companies_so'));
    }

    public function getPurchaseOrders(Request $request)
    {

        $purchaseOrders = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->Join('po_items', function ($join) {
                $join->on('po_items.po_id', '=', 'purchase_orders.id')
                    ->where('po_items.po_dispatch_item_status', '!=', 'Close');
            })
            ->Join('categories', 'categories.id', '=', 'po_items.item_category')
            ->where('purchase_orders.supplier_id', $request->company_id)
            ->get();


        return response()->json(['purchase_orders' => $purchaseOrders]);
    }

    public function getPoItems(Request $request)
    {
        $poItems = PoItem::join('categories', 'categories.id', '=', 'po_items.item_category')->where('po_id', $request->po_id)->get();
        // dd($poItems);
        return response()->json(['po_items' => $poItems]);
    }

    public function getSalesOrders(Request $request)
    {
        $companyId = $request->company_id;

        // $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
        // ->Join('so_items', 'so_items.so_id', '=', 'sales_orders.id')
        // ->Join('categories', 'categories.id', '=', 'so_items.item_category')->where('company_id', $companyId)
        // ->where('so_items.item_category', $request->ItemId)
        // ->get();
        $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->join('so_items', function ($join) {
                $join->on('so_items.so_id', '=', 'sales_orders.id')
                    ->where('so_items.so_dispatch_item_status', '!=', 'Close');
            })
            ->join('categories', 'categories.id', '=', 'so_items.item_category')
            ->where('sales_orders.company_id', $companyId)
            ->where('so_items.item_category', $request->ItemId)
            ->get();

        return response()->json(['salesOrders' => $salesOrders]);
    }

    // Method to get SO Items based on Sales Order ID
    public function getSoItems(Request $request)
    {
        $salesOrderId = $request->sales_order_id;

        // Retrieve items associated with the selected sales order
        $soItems = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')->where('so_id', $salesOrderId)->get();
        // dd($soItems);

        return response()->json(['soItems' => $soItems]);
    }

    public function getItemDetails(Request $request)
    {
        // dd($request);
        $itemId = $request->item_id;
        $po_items = PoItem::join('purchase_orders', 'po_items.po_id', '=', 'purchase_orders.id')
        ->join('categories', 'categories.id', '=', 'po_items.item_category')
        ->select('purchase_orders.*', 'categories.*', 'po_items.*', 'po_items.price as po_price')
        ->where('po_item_no', $request->poItemNo)->first();

        // Retrieve the item and its sub-items based on the item ID
        $item = Category::where('id', $itemId)->first();
        $subItems = SubCategory::where('category_id', $itemId)->get();
        $freight_insurance = FreightRate::latest()->first();
        // dd($subItems);

        // Return response with both item and sub-item details
        return response()->json(['item_details' => $item, 'subItems' => $subItems, 'po_items' => $po_items, 'freight' =>  $freight_insurance->freight_rate, 'insurance' =>  $freight_insurance->insurance_rate,]);
    }

    public function getItemDetailsSO(Request $request)
    {
        // dd($request);
        $so_items = SoItem::join('sales_orders', 'so_items.so_id', '=', 'sales_orders.id')
        ->join('categories', 'categories.id', '=', 'so_items.item_category')
        ->select('sales_orders.*', 'categories.*', 'so_items.*', 'so_items.price as so_price')
        ->where('so_item_no', $request->so_item_no)->first();

        $freight_insurance = FreightRate::latest()->first();


        return response()->json(['so_items' => $so_items, 'freight_insurance' => $freight_insurance]);
    }


    public function storeDispatch(Request $request)
    {


        foreach ($request->quantity as $index => $quantity) {


            $so_item = SoItem::where('so_item_no', $request->so_item_no)->first();
            $po_item = PoItem::where('po_item_no', $request->po_item_no)->first();

            if (!$so_item || !$po_item) {
                return redirect()->back()->with('msg', 'Please select atleast one PO Item or SO Item.');
            }

            if (($request->quantity[$index] > $so_item->so_dispatch_rest_qty) || ($request->quantity[$index] > $po_item->po_dispatch_rest_qty)) {
                return redirect()->back()->with('msg', 'Dispatch Rest Quantity Less than Dispatched Quantity.');
            }
        }

        // Loop through the quantities and sub_cat_ids to save each dispatch entry
        foreach ($request->quantity as $index => $quantity) {
            $so_item = SoItem::where('so_item_no', $request->so_item_no)->first();
            $po_item = PoItem::where('po_item_no', $request->po_item_no)->first();

            $dispatch = new Dispatch();
            $dispatch->date = $request->date;
            $dispatch->po_company_id = $request->po_company_id;
            $dispatch->so_company_id = $request->so_company_id;
            $dispatch->po_id = $po_item->po_id;
            $dispatch->so_id = $so_item->so_id;
            $dispatch->po_item_id = $po_item->id;
            $dispatch->so_item_id = $so_item->id;
            $dispatch->category_id = $request->cat_id[$index]; // Get the sub-category ID based on index
            $dispatch->subcategory_id = $request->sub_cat_id[$index]; // Get the quantity based on index
            $dispatch->dispatched_quantity = $request->quantity[$index];
            $dispatch->conv_rate = $request->conv_rate[$index];
            $dispatch->dispatch_unit_price = $request->dispatch_unit_price_actual[$index];
            $dispatch->dispatch_freight = $request->dispatch_freight[$index];

            if ($request->insurance_status[$index] == 'yes') {
                $dispatch->dispatch_other = $request->dispatch_other[$index];
                $dispatch->dispatch_so_other = $request->dispatch_so_other[$index];
            }else{
                $dispatch->dispatch_other = 0;
                $dispatch->dispatch_so_other = 0;
            }
            
            $dispatch->dispatch_total = $request->dispatch_total[$index];
            $dispatch->vehicle_number = $request->vehicle_number;

            $dispatch->dispatch_so_unit_price = $request->dispatch_so_unit_price_actual[$index];
            $dispatch->dispatch_so_freight = $request->dispatch_so_freight[$index];
           
            $dispatch->dispatch_so_total = $request->dispatch_so_total[$index];
            $dispatch->receiver_person = $request->receiver_person;

            $dispatch->remarks = $request->remarks;
            $dispatch->save();

    
            $actual_so_dispatch_qty = ($so_item->so_dispatch_rest_qty - $dispatch->dispatched_quantity);
            $actual_po_dispatch_qty = ($po_item->po_dispatch_rest_qty - $dispatch->dispatched_quantity);

            $so_item->update(['so_dispatch_rest_qty' => $actual_so_dispatch_qty]);
            $po_item->update(['po_dispatch_rest_qty' => $actual_po_dispatch_qty]);

            if ($actual_so_dispatch_qty == 0) {
                $so_item->update(['so_dispatch_item_status' => 'Close']);
            }
            else{
                $so_item->update(['so_dispatch_item_status' => 'Partial Pending']);
            }
            if ($actual_po_dispatch_qty == 0) {
                $po_item->update(['po_dispatch_item_status' => 'Close']);
            }else{
                $po_item->update(['po_dispatch_item_status' => 'Partial Pending']);
            }
        }
        return redirect()->route('dispatch.index')->with('success', 'Dispatch details saved successfully.');
    }


    public function editDispatch($id)
    {
        $disaptch_data = Dispatch::leftjoin('so_items', 'dispatches.so_item_id', '=', 'so_items.id')
            ->leftjoin('po_items', 'dispatches.po_item_id', '=', 'po_items.id')
            ->leftjoin('companies as po_company', 'dispatches.po_company_id', '=', 'po_company.id')
            ->leftjoin('companies as so_company', 'dispatches.so_company_id', '=', 'so_company.id')
            ->leftjoin('sales_orders', 'dispatches.so_id', '=', 'sales_orders.id')
            ->leftjoin('purchase_orders', 'dispatches.po_id', '=', 'purchase_orders.id')
            ->leftjoin('categories', 'dispatches.category_id', '=', 'categories.id')
            ->leftjoin('subcategories', 'dispatches.subcategory_id', '=', 'subcategories.id')
            ->select(
                'dispatches.*',
                'so_items.so_dispatch_rest_qty',
                'po_items.po_dispatch_rest_qty',
                'po_company.company_name as po_company',
                'so_company.company_name as so_company',
                'sales_orders.so_number',
                'purchase_orders.document_number as po_number',
                'sales_orders.date as so_date',
                'purchase_orders.date as po_date',
                'categories.name as category_name',
                'subcategories.sub_category as sub_category_name',
                'po_items.po_item_no',
                'so_items.so_item_no',
                'po_items.qty as po_qty',
                'so_items.qty as so_qty',
                'po_items.id as po_item_id',
                'so_items.id as so_item_id',
                'dispatches.id as dispatch_id',
            )
            ->where('dispatches.id', $id)
            ->first();

            $freight_insurance = FreightRate::latest()->first();

            $dispatch_po_price = ($disaptch_data->dispatch_unit_price + $disaptch_data->conv_rate + $disaptch_data->dispatch_freight + $disaptch_data->dispatch_other);
            $dispatch_so_price = ($disaptch_data->dispatch_so_unit_price + $disaptch_data->conv_rate + $disaptch_data->dispatch_freight + $disaptch_data->dispatch_other);

        // dd($disaptch_data);
        $sub_items = SubCategory::where('category_id',  $disaptch_data->category_id)->get();


        $so_item = SoItem::join('sales_orders', 'so_items.so_id', '=', 'sales_orders.id')
        ->leftjoin('categories', 'so_items.item_category', '=', 'categories.id')
        ->select('sales_orders.*', 'categories.*', 'so_items.*', 'so_items.price as so_price')
        ->where('so_items.id', $disaptch_data->so_item_id)->first();


        $po_item = PoItem::join('purchase_orders', 'po_items.po_id', '=', 'purchase_orders.id')
        ->leftjoin('categories', 'po_items.item_category', '=', 'categories.id')
        ->select('purchase_orders.*', 'categories.*', 'po_items.*', 'po_items.price as po_price')
        ->where('po_items.id', $disaptch_data->po_item_id)->first();

        // dd( $so_item,  $po_item);

        return view('dispatch.edit', compact('disaptch_data', 'sub_items', 'so_item', 'po_item', 'dispatch_po_price', 'dispatch_so_price', 'freight_insurance'));
    }

    public function updateDispatch(Request $request, $id)
    {

        // dd($request);
        $old_dispatch = Dispatch::where('id', $id)->first();
        foreach ($request->quantity as $index => $quantity) {
            $so_item = SoItem::where('id', $old_dispatch->so_item_id)->first();
            $po_item = PoItem::where('id', $old_dispatch->po_item_id)->first();

            $old_so_rest_qty_check = ($old_dispatch->dispatched_quantity + $so_item->so_dispatch_rest_qty);
            $old_po_rest_qty_check = ($old_dispatch->dispatched_quantity + $po_item->po_dispatch_rest_qty);

            if (($request->quantity[$index] > $old_so_rest_qty_check) || ($request->quantity[$index] > $old_po_rest_qty_check)) {
                return redirect()->back()->with('msg', 'Dispatch Rest Quantity Less than Dispatched Quantity.');
            }
        }
        // Loop through the quantities and sub_cat_ids to save each dispatch entry
        foreach ($request->quantity as $index => $quantity) {
            $so_item = SoItem::where('id', $old_dispatch->so_item_id)->first();
            $po_item = PoItem::where('id', $old_dispatch->po_item_id)->first();

            $old_so_rest_qty = ($old_dispatch->dispatched_quantity + $so_item->so_dispatch_rest_qty);
            $old_po_rest_qty = ($old_dispatch->dispatched_quantity + $po_item->po_dispatch_rest_qty);


            $dispatch = Dispatch::where('id', $id)->first();

            $dispatch->date = $request->date;
            $dispatch->dispatched_quantity = $request->quantity[$index];
            $dispatch->conv_rate = $request->conv_rate[$index];
            $dispatch->dispatch_unit_price = $request->dispatch_unit_price_actual[$index];
            $dispatch->dispatch_freight = $request->dispatch_freight[$index];
          
            $dispatch->dispatch_total = $request->dispatch_total[$index];


            $dispatch->dispatch_so_unit_price = $request->dispatch_so_unit_price_actual[$index];
            $dispatch->dispatch_so_freight = $request->dispatch_so_freight[$index];
            if ($request->insurance_status[$index] == 'yes') {
                // dd($request->dispatch_so_other_actual[$index]);
                $dispatch->dispatch_other = $request->dispatch_so_other_actual[$index];
                $dispatch->dispatch_so_other = $request->dispatch_so_other_actual[$index];
            }else{
                $dispatch->dispatch_other = 0;
                $dispatch->dispatch_so_other = 0;
            }
           
            $dispatch->dispatch_so_total = $request->dispatch_so_total[$index];
            $dispatch->receiver_person = $request->receiver_person;
            
            $dispatch->vehicle_number = $request->vehicle_number;
            $dispatch->remarks = $request->remarks;
            $dispatch->save();

            $actual_so_dispatch_qty = ($old_so_rest_qty - $dispatch->dispatched_quantity);
            $actual_po_dispatch_qty = ($old_po_rest_qty - $dispatch->dispatched_quantity);

            $so_item->update(['so_dispatch_rest_qty' => $actual_so_dispatch_qty]);
            $po_item->update(['po_dispatch_rest_qty' => $actual_po_dispatch_qty]);

            if ($actual_so_dispatch_qty == 0) {
                $so_item->update(['so_dispatch_item_status' => 'Close']);
            }
            else{
                $so_item->update(['so_dispatch_item_status' => 'Partial Pending']);
            }
            if ($actual_po_dispatch_qty == 0) {
                $po_item->update(['po_dispatch_item_status' => 'Close']);
            }else{
                $po_item->update(['po_dispatch_item_status' => 'Partial Pending']);
            }
        }
        return redirect()->route('dispatch.index')->with('update', 'Dispatch updated successfully.');
    }

    public function destroyDispatch($id)
    {

        $old_dispatch = Dispatch::where('id', $id)->first();

        $so_item = SoItem::where('id', $old_dispatch->so_item_id)->first();
        $po_item = PoItem::where('id', $old_dispatch->po_item_id)->first();

        $old_so_rest_qty = ($old_dispatch->dispatched_quantity + $so_item->so_dispatch_rest_qty);
        $old_po_rest_qty = ($old_dispatch->dispatched_quantity + $po_item->po_dispatch_rest_qty);

        $so_item->update(['so_dispatch_rest_qty' => $old_so_rest_qty]);
        $po_item->update(['po_dispatch_rest_qty' => $old_po_rest_qty]);

        if ($old_so_rest_qty < $so_item->qty) {
            $so_item->update(['so_dispatch_item_status' => 'Partial Pending']);
        }
        else{
            $so_item->update(['so_dispatch_item_status' => 'Open']);
        }
        if ($old_po_rest_qty < $po_item->qty) {
            $po_item->update(['po_dispatch_item_status' => 'Partial Pending']);
        }else{
            $po_item->update(['po_dispatch_item_status' => 'Open']);
        }

        Dispatch::where('id', $id)->delete();
        return redirect()->route('dispatch.index')->with('delete', 'Dispatch deleted successfully.');
    }

    public function get_conv_price(Request $request)
    {
        $cov_rates = ConvRate::where('subcategory_id', $request->subcategory_item_id)->latest()->first();
        return response($cov_rates);
    }

    public function get_dispatch_payable_total(Request $request)
    {

$dispatch_data = Dispatch::where('id', $request->dispatch_id)->first();
// dd($dispatch_data);
return response($dispatch_data);
        // return response()->json([
        //     'rows_data' => $dispatch_data
        // ]);
       
    }
}
