<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseItem;
use App\Models\Company;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;
use App\Models\SoItem;
use App\Models\PoItem;
use App\Models\InventoryTransaction;
use App\Models\PurchaseSellMatch;

class ManualMatching extends Controller
{
    public function index()
    {
       
        $suppliers = Company::where('type','=','supplier')->get();
        $companies = Company::where('type','=','supplier')->get();
        $buyers = Company::where('type','=','buyer')->get(); // Retrieve all companies
        $transactions = InventoryTransaction::all();
        $manual_match = PurchaseSellMatch::join('purchase_orders', 'purchase_sell_match.po_id', '=', 'purchase_orders.id')
        ->join('sales_orders', 'purchase_sell_match.so_id', '=', 'sales_orders.id')
        ->select(
            'purchase_sell_match.id as match_id',
            'purchase_sell_match.created_at',             
            'purchase_orders.id as po_id',
            'purchase_orders.document_number as po_number',
            'purchase_orders.rest_quantity as po_rest_quantity',  
            'purchase_orders.match_position as po_match_position',
            'sales_orders.id as so_id',
            'sales_orders.so_number as so_number',
            'sales_orders.rest_quantity as so_rest_quantity',     
            'sales_orders.match_position as so_match_position',
            'purchase_sell_match.matched_quantity'
        )
        ->get();
        $purchases =  PurchaseOrder::join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
        ->join('categories', 'purchase_orders.category', '=', 'categories.id')
        ->join('subcategories', 'purchase_orders.sub_category_id', '=', 'subcategories.id')
        ->whereIn('purchase_orders.status', ['Open', 'Partial Received'])
        ->select('categories.*', 'subcategories.sub_category', 'companies.*', 'purchase_orders.*', 'purchase_orders.date as date', 'purchase_orders.created_at as po_created_at', 'purchase_orders.id as po_id')
        ->orderBy('purchase_orders.created_at', 'desc')
        ->get();

       
        $po_data = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
       
        ->select('*', 'purchase_orders.id as id')
        ->get();

        $sales_order = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')      
        ->select('*', 'sales_orders.id as id')
        ->orderBy('sales_orders.id', 'desc')
        ->get();
            
    
// dd($sales_order);
        return view('manual_matching.index',compact('companies','transactions','buyers','suppliers','manual_match','purchases','sales_order','po_data','sales_order'));
    }

    public function showOpenPurchases(Request $request) 
    {
    //  dd($request);
      $companyId = $request->input('company_id');

      $purchases = PurchaseOrder::join('companies','companies.id','=','purchase_orders.supplier_id')->where('supplier_id', $companyId) 
                                  ->where('rest_quantity', '>', 0) 
                                  ->get();
// dd($purchases);
        $buyers = Company::where('type','=','buyer')->get();
  
        return view('manual_matching.open_purchases',compact('purchases','buyers'));
    }





public function match_inventory(Request $request)
{
    $companyId = $request->input('company_id');

    // Fetch the company to determine its type
    $company = Company::find($companyId);
    if (!$company) {
        return redirect()->back()->with('error', 'Company not found.');
    }

    if ($company->type === 'supplier') {
        dd(1);
        $purchases = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->join('categories', 'categories.id', '=', 'purchase_orders.category')
            ->join('subcategories', 'subcategories.id', '=', 'purchase_orders.sub_category_id')
            ->where('purchase_orders.supplier_id', $companyId)
            ->where('purchase_orders.rest_quantity', '>', 0)
            ->select('purchase_orders.*', 'companies.company_name as company_name', 'categories.name as category_name', 'subcategories.sub_category as sub_category_name')
            ->get();

        if ($purchases->isEmpty()) {
            return redirect()->back()->with('error', 'No purchase orders available for this supplier.');
        }
        $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
                ->where('sales_orders.match_position', 'open') // Filter for open positions
                ->select('sales_orders.*', 'companies.company_name')
                ->get();

        return view('manual_matching.match_inventory', compact('purchases', 'company','salesOrders'));

    } elseif ($company->type === 'buyer') {
        dd(1);
        $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')     
        ->where('sales_orders.match_position', 'open')
        ->select('sales_orders.*', 'companies.company_name')
        ->get();
        dd($salesOrders);

        if ($salesOrders->isEmpty()) {
            return redirect()->back()->with('error', 'No sales orders available for this buyer.');
        }
        $purchaseOrders = PurchaseOrder::where('match_position', 'open') // Adjust this based on how you define 'open' orders
        ->select('purchase_orders.*') // Add any other necessary fields
        ->get();

        if ($purchaseOrders->isEmpty()) {
            return redirect()->back()->with('error', 'No sales orders available for this buyer.');
        }

        return view('manual_matching.match_inventory_buyer', compact('salesOrders', 'company','purchaseOrders'));
    } else {
        return redirect()->back()->with('error', 'Invalid company type.');
    }
}


// public function storePurSellMatch(Request $request)
// {
//     foreach ($request->selected_orders as $salesOrderId) {
//         $purchaseOrderId = $request->input('purchase_order_id');
        
        
//         $salesOrder = SalesOrder::find($salesOrderId);
//         $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
        
        
//         if (!$salesOrder || !$purchaseOrder) {
//             continue;
//         }
        
        
//         $matchedQuantity = $request->input('matched_quantity.'.$salesOrderId, 0);
        
//         if ($matchedQuantity <= 0 || $matchedQuantity > $salesOrder->rest_quantity || $matchedQuantity > $purchaseOrder->rest_quantity) {
//             // dd(2);
            
//             continue; 
//         }
        
        
//         $remainingSoQuantity = $salesOrder->rest_quantity - $matchedQuantity;
//         // dd($remainingSoQuantity);
      
//         $remainingPoQuantity = $purchaseOrder->rest_quantity - $matchedQuantity;

       
//         PurchaseSellMatch::create([
//             'so_id' => $salesOrderId,
//             'po_id' => $purchaseOrderId,
//             'matched_quantity' => $matchedQuantity,
//             'po_rest_quantity' =>  $remainingPoQuantity,
//             'so_rest_quantity' =>  $remainingSoQuantity,



//         ]);

      
//         $salesOrder->update(['rest_quantity' => $remainingSoQuantity]);
//         $purchaseOrder->update(['rest_quantity' => $remainingPoQuantity]);

   
//         $salesOrder->update(['match_position' => $remainingSoQuantity > 0 ? 'open' : 'close']);
//         $purchaseOrder->update(['match_position' => $remainingPoQuantity > 0 ? 'open' : 'close']);
//     }

 
//     return redirect()->route('view.all')->with('success', 'Selected Sales Orders have been matched successfully.');
// }


public function match_purchase($id)
{
    
    $purchaseOrder = PurchaseOrder::join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
    ->join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
    ->select('companies.company_name','purchase_orders.*','po_items.*','po_items.id as po_item_id')
    ->find($id);

   
    if (!$purchaseOrder) {
        return response()->json(['error' => 'Purchase Order not found'], 404);
    }


$po_data = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
    ->join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
    ->join('categories', 'categories.id', '=', 'po_items.item_category')
    ->join('subcategories', 'subcategories.id', '=', 'po_items.item_subcategory')
    ->where('purchase_orders.id', $id)
    ->select(
        'purchase_orders.id as purchase_order_id',
        'companies.company_name as supplier_name',
        'purchase_orders.document_number',
        'po_items.id as po_item_id',
        'po_items.po_item_no as po_item_no',
        'po_items.qty',
        'po_items.unit_price',
        'po_items.price',
        'categories.id as category_id',  // Ensure you select the category ID
        'subcategories.id as subcategory_id', 
        'categories.name as category_name', 'subcategories.sub_category as sub_category_name'// Ensure you select the subcategory ID
    )
    ->get();

$categoryIds = $po_data->pluck('category_id')->unique()->toArray(); // Unique category IDs
$subcategoryIds = $po_data->pluck('subcategory_id')->unique()->toArray(); // Unique subcategory IDs

$salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
    ->join('so_items', 'so_items.so_id', '=', 'sales_orders.id')
    ->join('categories', 'categories.id', '=', 'so_items.item_category')
    ->join('subcategories', 'subcategories.id', '=', 'so_items.item_subcategory')
    ->where('sales_orders.match_position', 'open')
    ->whereIn('so_items.item_category', $categoryIds)  // Match category IDs
    ->whereIn('so_items.item_subcategory', $subcategoryIds) // Match subcategory IDs
    ->select('sales_orders.*', 'companies.company_name', 'so_items.id as so_item_id','so_items.*', 'categories.name as category_name', 'subcategories.sub_category as sub_category_name')
    ->get();

// dd($salesOrders);
$manual_match = PurchaseSellMatch::join('purchase_orders', 'purchase_sell_match.po_id', '=', 'purchase_orders.id')
    ->join('po_items','po_items.po_id','=','purchase_orders.id')
    ->join('sales_orders', 'purchase_sell_match.so_id', '=', 'sales_orders.id')   
    ->join('so_items','so_items.so_id','=','sales_orders.id')
    ->join('companies as so_companies', 'so_companies.id', '=', 'sales_orders.company_id')   
    ->join('companies as po_companies', 'po_companies.id', '=', 'purchase_orders.supplier_id')
    ->join('categories as po_categories', 'po_categories.id', '=', 'po_items.item_category') // Join categories for Purchase Orders
    ->join('subcategories as po_subcategories', 'po_subcategories.id', '=', 'po_items.item_subcategory') // Join subcategories for Purchase Orders
    ->join('categories as so_categories', 'so_categories.id', '=', 'so_items.item_category') // Join categories for Sales Orders
    ->join('subcategories as so_subcategories', 'so_subcategories.id', '=', 'so_items.item_subcategory') // Join subcategories for Sales Orders
    ->select(
        'purchase_sell_match.id as match_id',
        'purchase_sell_match.created_at',
        'purchase_sell_match.*',             
        'purchase_orders.id as po_id',
        'purchase_orders.document_number as po_number',
        'po_companies.company_name as po_company_name',  
        
        'purchase_orders.match_position as po_match_position',
        'sales_orders.id as so_id',
        'sales_orders.so_number as so_number',
        'so_companies.company_name as so_company_name',  
             
        'sales_orders.match_position as so_match_position',
        'purchase_sell_match.matched_quantity',
        'po_categories.name as po_category_name', // Select Purchase Order category name
        'po_subcategories.sub_category as po_sub_category_name', // Select Purchase Order subcategory name
        'so_categories.name as so_category_name', // Select Sales Order category name
        'so_subcategories.sub_category as so_sub_category_name' 
    )
    ->get();


//    dd($manual_match);
    return view('manual_matching.match_purchase', compact('purchaseOrder','salesOrders','manual_match','po_data'));
}



public function storePurSellMatch(Request $request)
{
    
    $purchaseOrderId = $request->input('purchase_order_id');
    $salesOrderId = $request->input('sales_order_id');

   
    $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

    
    if (!$purchaseOrder) {
        return redirect()->back()->with('error', 'Invalid Purchase Order');
    }

  
    foreach ($request->selected_so_items as $soItemId) {
    
        $matchedQuantity = $request->input('matched_quantity.' . $soItemId, 0);
        
       
        $poItemId = $request->input('po_item_id');
        
     
        $salesOrderItem = SoItem::find($soItemId);
        $purchaseOrderItem = PoItem::find($poItemId);
        
        
        if (!$salesOrderItem || !$purchaseOrderItem) {
            continue; 
        }

       
        if ($matchedQuantity <= 0 || 
            $matchedQuantity > $salesOrderItem->so_rest_qty || 
            $matchedQuantity > $purchaseOrderItem->po_rest_qty) {
            continue; 
        }


         // Check if the purchase order item exists in the matching context
         $purchaseOrderItemExists = PurchaseSellMatch::where('po_item_id', $poItemId)
         ->where('so_item_id', $soItemId)
         ->exists();

     // If the purchase order item does not exist, show an error
     if (!$purchaseOrderItemExists) {
         return redirect()->back()->with('error', 'Sales Order Item ID: ' . $soItemId . ' does not exist in Purchase Order.');
     }
    //     if ($salesOrderItem->category_id !== $purchaseOrderItem->category || 
    //     $salesOrderItem->sub_category_id !== $purchaseOrderItem->sub_category_id) {
    //     return redirect()->back()->with('error', 'Categories and Subcategories do not match for Sales Order Item ID: ' . $soItemId);
    // }
      
        $remainingSoItemQuantity = $salesOrderItem->so_rest_qty - $matchedQuantity;
        $remainingPoItemQuantity = $purchaseOrderItem->po_rest_qty - $matchedQuantity;

       
        $purchaseSellMatch = PurchaseSellMatch::create([
            'so_item_id' => $soItemId, // The ID of the specific sales order item being matched
            'po_item_id' => $poItemId, // The ID of the specific purchase order item being matched
            'matched_quantity' => $matchedQuantity, // The quantity matched
            'po_item_rest_quantity' => $remainingPoItemQuantity, // Remaining quantity in the PO after matching
            'so_item_rest_quantity' => $remainingSoItemQuantity, // Remaining quantity in the SO after matching
        ]);

        $salesOrderItem->update(['so_rest_qty' => $remainingSoItemQuantity]);
        $purchaseOrderItem->update(['po_rest_qty' => $remainingPoItemQuantity]);

        $purchaseSellMatch->update([
            'so_id' => $salesOrderId, 
            'po_id' => $purchaseOrderId, 
            'so_item_qty' => $salesOrderItem->so_rest_qty, 
            'po_item_qty' => $purchaseOrderItem->po_rest_qty, 
        ]);
        

      
       
        // $salesOrderItem->update(['so_match_position' => $remainingSoItemQuantity > 0 ? 'open' : 'close']);
        // $purchaseOrderItem->update(['po_match_position' => $remainingPoItemQuantity > 0 ? 'open' : 'close']);
    }

    // Redirect back to the view page with a success message
    return redirect()->route('view.all')->with('success', 'Selected Sales Order items have been matched successfully.');
}









public function storePurSellMatchBuyer(Request $request)
{
    
    $salesOrderId = $request->input('sales_order_id');

    
    $salesOrder = SalesOrder::find($salesOrderId);

    
    if (!$salesOrder) {
        return redirect()->route('view.all')->with('error', 'The selected Sales Order does not exist.');
    }

   
    foreach ($request->input('matched_quantity') as $purchaseOrderId => $matchedQuantity) {
       
        if ($matchedQuantity <= 0) {
            continue; 
        }
        
       
        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
        
     
        if (!$purchaseOrder) {
            continue;
        }

      
        $soQuantity = $salesOrder->rest_quantity;
        $poQuantity = $purchaseOrder->rest_quantity;
        
        if ($matchedQuantity > $soQuantity || $matchedQuantity > $poQuantity) {
          
            $matchedQuantity = min($soQuantity, $poQuantity);
        }
        
       
        $remainingSoQuantity = $soQuantity - $matchedQuantity;
        $remainingPoQuantity = $poQuantity - $matchedQuantity;
      

        
        PurchaseSellMatch::create([
            'so_id' => $salesOrderId,
            'po_id' => $purchaseOrderId,
            'matched_quantity' => $matchedQuantity,
            'po_rest_quantity' =>  $remainingPoQuantity,
            'so_rest_quantity' =>  $remainingSoQuantity,

        ]);

       
        $salesOrder->update(['rest_quantity' => $remainingSoQuantity]);
        $purchaseOrder->update(['rest_quantity' => $remainingPoQuantity]);

    
        $salesOrder->update(['match_position' => $remainingSoQuantity > 0 ? 'open' : 'close']);
        $purchaseOrder->update(['match_position' => $remainingPoQuantity > 0 ? 'open' : 'close']);
    }

    
    return redirect()->route('view.all')->with('success', 'Selected Purchase Order has been matched successfully with the selected Sales Order.');
}







public function view_all()
{
    $suppliers = Company::where('type','=','supplier')->get();
    $companies = Company::where('type','=','supplier')->get();
    $buyers = Company::where('type','=','buyer')->get(); // Retrieve all companies
    $transactions = InventoryTransaction::all();
    $manual_match = PurchaseSellMatch::join('purchase_orders', 'purchase_sell_match.po_id', '=', 'purchase_orders.id')
    ->join('sales_orders', 'purchase_sell_match.so_id', '=', 'sales_orders.id')
    // Alias the companies table for sales orders
    ->join('companies as so_companies', 'so_companies.id', '=', 'sales_orders.company_id')
    // Alias the companies table for purchase orders (suppliers)
    ->join('companies as po_companies', 'po_companies.id', '=', 'purchase_orders.supplier_id')
    ->select(
        'purchase_sell_match.id as match_id',
        'purchase_sell_match.created_at',
        'purchase_sell_match.*',             
        'purchase_orders.id as po_id',
        'purchase_orders.document_number as po_number',
        'po_companies.company_name as po_company_name',  // Aliased to po_companies
        
        'purchase_orders.match_position as po_match_position',
        'sales_orders.id as so_id',
        'sales_orders.so_number as so_number',
        'so_companies.company_name as so_company_name',  // Aliased to so_companies
             
        'sales_orders.match_position as so_match_position',
        'purchase_sell_match.matched_quantity'
    )
    ->get();



        // dd($manual_match);
    return view('manual_matching.view_all',compact('companies','transactions','buyers','suppliers','manual_match'));
}



    




public function match_sales($id)
{
    $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')    
    ->where('sales_orders.id', $id)
    ->select('sales_orders.*', 'companies.company_name')
    ->first();
    
    // dd($salesOrders);
    $purchaseOrders = PurchaseOrder::where('match_position', 'open') // Adjust this based on how you define 'open' orders
    ->select('purchase_orders.*') // Add any other necessary fields
    ->get();

    $manual_match = PurchaseSellMatch::join('purchase_orders', 'purchase_sell_match.po_id', '=', 'purchase_orders.id')
    ->join('po_items','po_items.po_id','=','purchase_orders.id')
    ->join('sales_orders', 'purchase_sell_match.so_id', '=', 'sales_orders.id')   
    ->join('so_items','so_items.so_id','=','sales_orders.id')
    ->join('companies as so_companies', 'so_companies.id', '=', 'sales_orders.company_id')   
    ->join('companies as po_companies', 'po_companies.id', '=', 'purchase_orders.supplier_id')
    ->join('categories as po_categories', 'po_categories.id', '=', 'po_items.item_category') // Join categories for Purchase Orders
    ->join('subcategories as po_subcategories', 'po_subcategories.id', '=', 'po_items.item_subcategory') // Join subcategories for Purchase Orders
    ->join('categories as so_categories', 'so_categories.id', '=', 'so_items.item_category') // Join categories for Sales Orders
    ->join('subcategories as so_subcategories', 'so_subcategories.id', '=', 'so_items.item_subcategory') // Join subcategories for Sales Orders
    ->select(
        'purchase_sell_match.id as match_id',
        'purchase_sell_match.created_at',
        'purchase_sell_match.*',             
        'purchase_orders.id as po_id',
        'purchase_orders.document_number as po_number',
        'po_companies.company_name as po_company_name',  
        
        'purchase_orders.match_position as po_match_position',
        'sales_orders.id as so_id',
        'sales_orders.so_number as so_number',
        'so_companies.company_name as so_company_name',  
             
        'sales_orders.match_position as so_match_position',
        'purchase_sell_match.matched_quantity',
        'po_categories.name as po_category_name', // Select Purchase Order category name
        'po_subcategories.sub_category as po_sub_category_name', // Select Purchase Order subcategory name
        'so_categories.name as so_category_name', // Select Sales Order category name
        'so_subcategories.sub_category as so_sub_category_name' 
    )
    ->get();


    return view('manual_matching.match_Sales', compact('salesOrders','purchaseOrders','manual_match'));    
}

}
