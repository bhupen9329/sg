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
        ->select('dispatches.*', 'so_items.so_dispatch_rest_qty', 'po_items.po_dispatch_rest_qty', 'po_company.company_name as po_company',
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
         'dispatches.id as dispatch_id',
         'dispatches.created_at as dispatch_date',

        )
        ->get();

        return view('dispatch.index', compact('disaptch_data'));
    }

    public function create()
    {
        $purchase_orders = PurchaseOrder::join('companies', 'companies.id','=','purchase_orders.supplier_id')
                                         ->get();
        // dd($purchase_orders);
        $sales_orders = SalesOrder::all();
        $companies = Company::all();
        // dd($purchase_orders);
        return view('dispatch.create',compact('purchase_orders','sales_orders','companies'));
    }

    public function getPurchaseOrders(Request $request)
{
    $purchaseOrders = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
    ->leftJoin('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
    ->leftJoin('categories', 'categories.id', '=', 'po_items.item_category')
    ->where('purchase_orders.supplier_id', $request->company_id)
   
    ->get();
    // dd($purchaseOrders);

    return response()->json(['purchase_orders' => $purchaseOrders]);
}

public function getPoItems(Request $request)
{
    $poItems = PoItem::join('categories','categories.id','=','po_items.item_category')->where('po_id', $request->po_id)->get();
    // dd($poItems);
    return response()->json(['po_items' => $poItems]);
}

public function getSalesOrders(Request $request)
{
    $companyId = $request->company_id;
    
    $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
    ->leftJoin('so_items', 'so_items.so_id', '=', 'sales_orders.id')
    ->leftJoin('categories', 'categories.id', '=', 'so_items.item_category')->where('company_id', $companyId)
    ->where('so_items.item_category', $request->ItemId)
    ->get();
    return response()->json(['salesOrders' => $salesOrders]);
}

// Method to get SO Items based on Sales Order ID
public function getSoItems(Request $request)
{
    $salesOrderId = $request->sales_order_id;
    
    // Retrieve items associated with the selected sales order
    $soItems = SoItem::join('categories','categories.id','=','so_items.item_category')->where('so_id', $salesOrderId)->get();
    // dd($soItems);
    
    return response()->json(['soItems' => $soItems]);
}

public function getItemDetails(Request $request)
{
    $itemId = $request->item_id;
    // dd($itemId);

    // Retrieve the item and its sub-items based on the item ID
    $item = Category::where('id', $itemId)->first();
    $subItems = SubCategory::where('category_id', $itemId)->get();
    // dd($subItems);

    // Return response with both item and sub-item details
    return response()->json(['item_details' => $item, 'subItems' => $subItems]);
}


public function storeDispatch(Request $request)
{

foreach ($request->quantity as $index => $quantity) {
    $so_item = SoItem::where('so_item_no', $request->so_item_no)->first();
    $po_item = PoItem::where('po_item_no', $request->po_item_no)->first();

    if(($request->quantity[$index] > $so_item->so_dispatch_rest_qty) || ($request->quantity[$index] > $po_item->po_dispatch_rest_qty)){
        return redirect()->back()->with('msg', 'Dispatch Rest Quantity Less than Dispatched Quantity.');
    }

}

 // Loop through the quantities and sub_cat_ids to save each dispatch entry
 foreach ($request->quantity as $index => $quantity) {
    $so_item = SoItem::where('so_item_no', $request->so_item_no)->first();
    $po_item = PoItem::where('po_item_no', $request->po_item_no)->first();

    $dispatch = new Dispatch();
    $dispatch->po_company_id = $request->po_company_id; 
    $dispatch->so_company_id = $request->so_company_id; 
    $dispatch->po_id = $po_item->po_id; 
    $dispatch->so_id = $so_item->so_id; 
    $dispatch->po_item_id = $po_item->id; 
    $dispatch->so_item_id =$so_item->id;
    $dispatch->category_id = $request->cat_id[$index]; // Get the sub-category ID based on index
    $dispatch->subcategory_id = $request->sub_cat_id[$index]; // Get the quantity based on index
    $dispatch->dispatched_quantity = $request->quantity[$index];
    $dispatch->conv_rate = $request->conv_rate[$index];
    $dispatch->vehicle_number = $request->vehicle_number; 
    $dispatch->remarks = $request->remarks; 
    $dispatch->save(); 
    $actual_so_dispatch_qty = ($so_item->so_dispatch_rest_qty - $dispatch->dispatched_quantity);
    $actual_po_dispatch_qty = ($po_item->po_dispatch_rest_qty - $dispatch->dispatched_quantity);
// dd( $actual_so_dispatch_qty,  $actual_po_dispatch_qty);
    $so_item->update(['so_dispatch_rest_qty' => $actual_so_dispatch_qty]);
    $po_item->update(['po_dispatch_rest_qty' => $actual_po_dispatch_qty]);

    if($actual_so_dispatch_qty == 0){
        $so_item->update(['so_dispatch_item_status' => 'Close']);
    }
    if($actual_po_dispatch_qty == 0){
        $po_item->update(['po_dispatch_item_status' => 'Close']);
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
    ->select('dispatches.*', 'so_items.so_dispatch_rest_qty', 'po_items.po_dispatch_rest_qty', 'po_company.company_name as po_company',
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
     'dispatches.id as dispatch_id',
    )
    ->where('dispatches.id', $id)
    ->first();
    // dd($disaptch_data);
    $sub_items = SubCategory::where('category_id',  $disaptch_data->category_id)->get();
    return view('dispatch.edit',compact('disaptch_data', 'sub_items'));
}

public function updateDispatch(Request $request, $id)
{
  $old_dispatch = Dispatch::where('id', $id)->first();
foreach ($request->quantity as $index => $quantity) {
    $so_item = SoItem::where('id', $old_dispatch->so_item_id)->first();
    $po_item = PoItem::where('id', $old_dispatch->po_item_id)->first();

    if(($request->quantity[$index] > $so_item->so_dispatch_rest_qty) || ($request->quantity[$index] > $po_item->po_dispatch_rest_qty)){
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
    $dispatch->dispatched_quantity = $request->quantity[$index];
    $dispatch->conv_rate = $request->conv_rate[$index];
    $dispatch->vehicle_number = $request->vehicle_number; 
    $dispatch->remarks = $request->remarks; 
    $dispatch->save(); 

    $actual_so_dispatch_qty = ($old_so_rest_qty - $dispatch->dispatched_quantity);
    $actual_po_dispatch_qty = ($old_po_rest_qty - $dispatch->dispatched_quantity);

    $so_item->update(['so_dispatch_rest_qty' => $actual_so_dispatch_qty]);
    $po_item->update(['po_dispatch_rest_qty' => $actual_po_dispatch_qty]);

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

Dispatch::where('id', $id)->delete();
return redirect()->route('dispatch.index')->with('delete', 'Dispatch deleted successfully.');

}

public function get_conv_price(Request $request)
{
    $cov_rates = ConvRate::where('subcategory_id', $request->subcategory_item_id)->latest()->first();
    return response($cov_rates);
}

}
