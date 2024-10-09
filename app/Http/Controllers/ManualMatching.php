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
        'purchase_sell_match.*',             
        'purchase_orders.rest_quantity as po_rest_quantity',  
        'sales_orders.rest_quantity as so_rest_quantity',     
        'purchase_orders.match_position as po_match_position',
        'sales_orders.match_position as so_match_position'  
    )
    ->get();
// dd($manual_match);
        return view('manual_matching.index',compact('companies','transactions','buyers','suppliers','manual_match'));
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

    $purchases = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
    ->join('categories', 'categories.id', '=', 'purchase_orders.category')
    ->join('subcategories', 'subcategories.id', '=', 'purchase_orders.sub_category_id')
    ->where('purchase_orders.supplier_id', $companyId)
    ->where('purchase_orders.rest_quantity', '>', 0)
    ->select('purchase_orders.*', 'companies.company_name as company_name', 'categories.name as category_name','subcategories.sub_category as sub_category_name') 
    ->get();
    if ($purchases->isEmpty()) {
        return redirect()->back()->with('error', 'No purchase orders available for this company.');
    }

// dd($purchases);
    $buyers = Company::where('type', '=', 'buyer')->get();

    // Fetching only sales orders where match_position is 'open'
    $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
        ->where('sales_orders.match_position', 'open') // Filter for open positions
        ->select('sales_orders.*', 'companies.company_name')
        ->get();

    return view('manual_matching.match_inventory', compact('purchases', 'buyers', 'salesOrders'));
}

public function storePurSellMatch(Request $request)
{
    foreach ($request->selected_orders as $salesOrderId) {
       
        $purchaseOrderId = $request->input('purchase_order_id');

       
        $salesOrder = SalesOrder::find($salesOrderId);
        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

      
        if (!$salesOrder || !$purchaseOrder) {
            continue;
        }

       
        $soQuantity = $salesOrder->rest_quantity;
        $poQuantity = $purchaseOrder->rest_quantity;

        
        $matchedQuantity = min($soQuantity, $poQuantity);

      
        $remainingSoQuantity = $soQuantity - $matchedQuantity;
        $remainingPoQuantity = $poQuantity - $matchedQuantity;

       
        PurchaseSellMatch::create([
            'so_id' => $salesOrderId,
            'po_id' => $purchaseOrderId,
            'matched_quantity' => $matchedQuantity,
        ]);

      
        $salesOrder->update(['rest_quantity' => $remainingSoQuantity]);
        $purchaseOrder->update(['rest_quantity' => $remainingPoQuantity]);

      
        if ($remainingSoQuantity > 0) {
            $salesOrder->update(['status' => 'open']);
        } else {
            $salesOrder->update(['status' => 'close']);
        }

       
        if ($remainingPoQuantity > 0) {
            $purchaseOrder->update(['status' => 'close']);
        } else {
            $purchaseOrder->update(['status' => 'open']);
        }
    }

    return redirect()->route('manual.matching')->with('success', 'Selected Sales Orders have been matched successfully.');

}



    

    // public function get_buyer_list()
    // {
    //     $buyers = Company::where('type','=','buyer')->get();
    //     return view('manual_matching.open_purchases', compact('buyers')); // Return the view where the modal is defined
    // }
}
