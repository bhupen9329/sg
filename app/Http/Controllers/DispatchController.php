<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Company;
use App\Models\SalesOrder;
use App\Models\PoItem;
use App\Models\SoItem;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Dispatch;

class DispatchController extends Controller
{


        public function index()
    {
        return view('dispatch.index');
    }

    public function create()
    {
        $purchase_orders = PurchaseOrder::join('companies', 'companies.id','=','purchase_orders.supplier_id')->get();
        $sales_orders = SalesOrder::all();
        $companies = Company::all();
        // dd($purchase_orders);
        return view('dispatch.create',compact('purchase_orders','sales_orders','companies'));
    }

    public function getPurchaseOrders(Request $request)
{
    $purchaseOrders = PurchaseOrder::where('supplier_id', $request->company_id)->get();
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
    // dd($companyId);
  
    $salesOrders = SalesOrder::where('company_id', $companyId)->get(['id', 'so_number']);
    
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
    $ItemId = $request->item_id;
    
    // Retrieve items associated with the selected sales order
    $items = Category::where('id',  $ItemId)->first();
    $subItems = SubCategory::where('category_id', $ItemId)->get();

    // dd($subItems);


    
    return response()->json(['items' => $items, 'subItems' =>  $subItems]);
}

public function storeDispatch(Request $request)
{
//   dd($request);

 // Loop through the quantities and sub_cat_ids to save each dispatch entry
 foreach ($request->quantity as $index => $quantity) {
    $dispatch = new Dispatch();
    $dispatch->po_id = $request->po_number; // Assigning the PO number
    $dispatch->so_id = $request->sales_order_number; // Assigning the Sales Order number
    $dispatch->po_item_id = $request->po_item_id; // Assigning the PO item ID
    $dispatch->so_item_sub_category_id = $request->sub_cat_id[$index]; // Get the sub-category ID based on index
    $dispatch->so_item_qty = $request->conv_rate[$index]; // Get the quantity based on index
    $dispatch->dispatched_quantity = $quantity;
    // Optionally, you can add other fields here as needed

    $dispatch->save(); // Save the dispatch record
}


// Redirect or return a response
return redirect()->route('dispatch.index')->with('success', 'Dispatch details saved successfully.');
}



}
