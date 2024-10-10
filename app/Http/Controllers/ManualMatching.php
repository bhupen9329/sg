<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseItem;
use App\Models\Company;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;
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
        // Logic for supplier
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
        $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
        ->where('sales_orders.company_id', $companyId)
        ->where('sales_orders.match_position', 'open')
        ->select('sales_orders.*', 'companies.company_name')
        ->get();
        // dd($salesOrders);
        if ($salesOrders->isEmpty()) {
            return redirect()->back()->with('error', 'No sales orders available for this buyer.');
        }
        $purchaseOrders = PurchaseOrder::where('match_position', 'open') // Adjust this based on how you define 'open' orders
        ->select('purchase_orders.*') // Add any other necessary fields
        ->get();

        if ($salesOrders->isEmpty()) {
            return redirect()->back()->with('error', 'No sales orders available for this buyer.');
        }

        return view('manual_matching.match_inventory_buyer', compact('salesOrders', 'company','purchaseOrders'));
    } else {
        return redirect()->back()->with('error', 'Invalid company type.');
    }
}


public function storePurSellMatch(Request $request)
{
    // Loop through each selected sales order
    foreach ($request->selected_orders as $salesOrderId) {
        $purchaseOrderId = $request->input('purchase_order_id');

        // Fetch the sales order and purchase order
        $salesOrder = SalesOrder::find($salesOrderId);
        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

        // If either order is not found, skip to the next iteration
        if (!$salesOrder || !$purchaseOrder) {
            continue;
        }

        // Get the matched quantity for the current sales order from the request
        $matchedQuantity = $request->input('matched_quantity.'.$salesOrderId, 0);
        // Ensure matched quantity is valid
        if ($matchedQuantity <= 0 || $matchedQuantity > $salesOrder->rest_quantity || $matchedQuantity > $purchaseOrder->rest_quantity) {
            // dd(1);
            continue; // Skip this iteration if the quantity is invalid
        }
        
        // Calculate remaining quantities
        $remainingSoQuantity = $salesOrder->rest_quantity - $matchedQuantity;
        // dd($matchedQuantity);
        $remainingPoQuantity = $purchaseOrder->rest_quantity - $matchedQuantity;

        // Create the matching record
        PurchaseSellMatch::create([
            'so_id' => $salesOrderId,
            'po_id' => $purchaseOrderId,
            'matched_quantity' => $matchedQuantity,
            'po_rest_quantity' =>  $remainingPoQuantity,
            'so_rest_quantity' =>  $remainingSoQuantity,



        ]);

        // Update remaining quantities for both sales and purchase orders
        $salesOrder->update(['rest_quantity' => $remainingSoQuantity]);
        $purchaseOrder->update(['rest_quantity' => $remainingPoQuantity]);

        // Update the match position based on remaining quantities
        $salesOrder->update(['match_position' => $remainingSoQuantity > 0 ? 'open' : 'close']);
        $purchaseOrder->update(['match_position' => $remainingPoQuantity > 0 ? 'open' : 'close']);
    }

    // Redirect with a success message after the matching process
    return redirect()->route('view.all')->with('success', 'Selected Sales Orders have been matched successfully.');
}


public function storePurSellMatchBuyer(Request $request)
{
    // Fetch the selected sales order ID from the hidden input
    $salesOrderId = $request->input('sales_order_id');

    // Fetch the selected sales order
    $salesOrder = SalesOrder::find($salesOrderId);

    // If the sales order is not found, redirect with an error
    if (!$salesOrder) {
        return redirect()->route('view.all')->with('error', 'The selected Sales Order does not exist.');
    }

    // Iterate through the matched quantities
    foreach ($request->input('matched_quantity') as $purchaseOrderId => $matchedQuantity) {
        // Validate matched quantity
        if ($matchedQuantity <= 0) {
            continue; // Skip if the matched quantity is not valid
        }
        
        // Fetch the purchase order
        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
        
        // If the purchase order is not found, skip to the next iteration
        if (!$purchaseOrder) {
            continue;
        }

        // Determine the remaining quantities
        $soQuantity = $salesOrder->rest_quantity;
        $poQuantity = $purchaseOrder->rest_quantity;
        // Check if matched quantity exceeds the available quantities
        if ($matchedQuantity > $soQuantity || $matchedQuantity > $poQuantity) {
            // If the matched quantity exceeds the remaining quantity, adjust it
            $matchedQuantity = min($soQuantity, $poQuantity);
        }
        
        // Calculate remaining quantities
        $remainingSoQuantity = $soQuantity - $matchedQuantity;
        $remainingPoQuantity = $poQuantity - $matchedQuantity;
        // dd($remainingPoQuantity);

        // Create the matching record
        PurchaseSellMatch::create([
            'so_id' => $salesOrderId,
            'po_id' => $purchaseOrderId,
            'matched_quantity' => $matchedQuantity,
            'po_rest_quantity' =>  $remainingPoQuantity,
            'so_rest_quantity' =>  $remainingSoQuantity,

        ]);

        // Update remaining quantities for both sales and purchase orders
        $salesOrder->update(['rest_quantity' => $remainingSoQuantity]);
        $purchaseOrder->update(['rest_quantity' => $remainingPoQuantity]);

        // Update the match position based on remaining quantities
        $salesOrder->update(['match_position' => $remainingSoQuantity > 0 ? 'open' : 'close']);
        $purchaseOrder->update(['match_position' => $remainingPoQuantity > 0 ? 'open' : 'close']);
    }

    // Redirect with a success message after the matching process
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



    

public function match_purchase($id)
{
    
    $purchaseOrder = PurchaseOrder::find($id);
//     $purchases = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
//     ->join('categories', 'categories.id', '=', 'purchase_orders.category')
//     ->join('subcategories', 'subcategories.id', '=', 'purchase_orders.sub_category_id')
//     ->where('purchase_orders.supplier_id', $companyId)
//     ->where('purchase_orders.rest_quantity', '>', 0)
//     ->select('purchase_orders.*', 'companies.company_name as company_name', 'categories.name as category_name', 'subcategories.sub_category as sub_category_name')
//     ->get();

// if ($purchases->isEmpty()) {
//     return redirect()->back()->with('error', 'No purchase orders available for this supplier.');
// }

$salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
        ->where('sales_orders.match_position', 'open') 
        ->select('sales_orders.*', 'companies.company_name')
        ->get();
//    dd($salesOrders);
    return view('manual_matching.match_purchase', compact('purchaseOrder','salesOrders'));
}



public function match_sales()
{
    $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
  
    ->select('sales_orders.*', 'companies.company_name')
    ->get();

    $purchaseOrders = PurchaseOrder::where('match_position', 'open') // Adjust this based on how you define 'open' orders
    ->select('purchase_orders.*') // Add any other necessary fields
    ->get();


    return view('manual_matching.match_Sales', compact('salesOrders','purchaseOrders'));    
}

}
