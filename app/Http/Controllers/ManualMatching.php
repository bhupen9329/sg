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
use App\Models\SellPurchaseMatch;
use Illuminate\Support\Facades\DB;

class ManualMatching extends Controller
{
    public function index()
    {

        $suppliers = Company::where('type', '=', 'supplier')->get();
        $companies = Company::where('type', '=', 'supplier')->get();
        $buyers = Company::where('type', '=', 'buyer')->get(); // Retrieve all companies
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
            ->whereIn('purchase_orders.status', ['Open', 'Partial Received'])
            ->select('categories.*', 'companies.*', 'purchase_orders.*', 'purchase_orders.date as date', 'purchase_orders.created_at as po_created_at', 'purchase_orders.id as po_id')
            ->orderBy('purchase_orders.created_at', 'desc')
            ->get();


        $po_data = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->leftJoin('purchase_sell_match', 'po_items.id', '=', 'purchase_sell_match.po_item_id')
            ->select(
                'purchase_orders.*',
                'companies.company_name',
                'po_items.*',
                'po_items.id as id',
                'categories.name',
                DB::raw('SUM(purchase_sell_match.matched_quantity) as total_matched_quantity'),
                DB::raw('SUM(purchase_sell_match.so_item_price) as total_so_price'),
                DB::raw('SUM(purchase_sell_match.matched_quantity * purchase_sell_match.so_item_unit_price) / SUM(purchase_sell_match.matched_quantity) AS avg_price') // Corrected average price calculation

            )
            ->groupBy(
                'purchase_orders.id',
                'purchase_orders.supplier_id',
                'purchase_orders.category',
                'purchase_orders.sub_category_id',
                'purchase_orders.document_number',
                'purchase_orders.date',
                'purchase_orders.due_date',
                'purchase_orders.no_of_due_date',
                'purchase_orders.quantity',
                'purchase_orders.rest_quantity',
                'purchase_orders.price',
                'purchase_orders.mode',
                'purchase_orders.broker',
                'purchase_orders.remark',
                'purchase_orders.status',
                'purchase_orders.match_position',
                'purchase_orders.order_age',
                'purchase_orders.close_date',
                'purchase_orders.created_at',
                'purchase_orders.updated_at',

                'purchase_orders.total_amount',

                'purchase_orders.total_price',


                'purchase_orders.total_quantity',

                'po_items.id',
                'po_items.po_id',
                'po_items.item_category',
                'po_items.po_item_no',
                'po_items.item_subcategory',
                'po_items.qty',
                'po_items.po_rest_qty',
                'po_items.unit_price',
                'po_items.price',
                'po_items.created_at',
                'po_items.updated_at',
                'po_items.po_item_status',

                'categories.name',
                'companies.company_name',
                'po_items.id',
                'categories.id',
            )

            ->orderBy('categories.id', 'desc')
            ->get();

        // dd(  $po_data);


        $sales_order = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->leftJoin('purchase_sell_match', 'so_items.id', '=', 'purchase_sell_match.so_item_id')
            ->select(
                'sales_orders.*',
                'sales_orders.id as so_id',
                'companies.company_name',
                'so_items.*',
                'so_items.id as so_item_id',
                'categories.name',
                DB::raw('SUM(purchase_sell_match.matched_quantity) as total_matched_quantity'),
                DB::raw('SUM(purchase_sell_match.po_item_price) as total_po_price'),
                DB::raw('SUM(purchase_sell_match.matched_quantity * purchase_sell_match.po_item_unit_price) / SUM(purchase_sell_match.matched_quantity) AS avg_price') // Corrected average price calculation
            )

            ->groupBy(
                'sales_orders.id',
                'sales_orders.company_id',
                'sales_orders.virtual_store_id',
                'sales_orders.so_number',
                'sales_orders.address',
                'sales_orders.date',
                'sales_orders.due_date',
                'sales_orders.document_file',
                'sales_orders.terms_condition',
                'sales_orders.total_quantity',
                'sales_orders.rest_quantity',
                'sales_orders.total_amount',
                'sales_orders.total_price',
                'sales_orders.status',
                'sales_orders.match_position',
                'sales_orders.created_at',
                'sales_orders.updated_at',

                'so_items.id',
                'so_items.so_id',
                'so_items.so_item_no',
                'so_items.id',
                'so_items.item_category',
                'so_items.item_subcategory',
                'so_items.qty',
                'so_items.so_rest_qty',
                'so_items.unit_price',
                'so_items.price',
                'so_items.created_at',
                'so_items.updated_at',
                'so_items.so_item_status',

                'categories.name',
                'companies.company_name',
                'categories.id',
            )
            ->orderBy('categories.id', 'desc')
            ->get();



        // dd($sales_order);


        return view('manual_matching.index', compact('companies', 'transactions', 'buyers', 'suppliers', 'manual_match', 'purchases', 'sales_order', 'po_data'));
    }

    public function showOpenPurchases(Request $request)
    {
        //  dd($request);
        $companyId = $request->input('company_id');

        $purchases = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')->where('supplier_id', $companyId)
            ->where('rest_quantity', '>', 0)
            ->get();
        // dd($purchases);
        $buyers = Company::where('type', '=', 'buyer')->get();

        return view('manual_matching.open_purchases', compact('purchases', 'buyers'));
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
            $purchases = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
                ->join('categories', 'categories.id', '=', 'purchase_orders.category')
                ->where('purchase_orders.supplier_id', $companyId)
                ->where('purchase_orders.rest_quantity', '>', 0)
                ->select('purchase_orders.*', 'companies.company_name as company_name', 'categories.name as category_name')
                ->get();

            if ($purchases->isEmpty()) {
                return redirect()->back()->with('error', 'No purchase orders available for this supplier.');
            }
            $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
                ->where('sales_orders.match_position', 'open') // Filter for open positions
                ->select('sales_orders.*', 'companies.company_name', 'sales_orders.id as so_id')
                ->get();

            return view('manual_matching.match_inventory', compact('purchases', 'company', 'salesOrders'));
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

            return view('manual_matching.match_inventory_buyer', compact('salesOrders', 'company', 'purchaseOrders'));
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

        $po_data = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
            ->join('categories', 'categories.id', '=', 'po_items.item_category')
            ->select(
                'purchase_orders.id as purchase_order_id',
                'purchase_orders.date as po_date',
                'companies.company_name as supplier_name',
                'purchase_orders.document_number',
                'po_items.id as po_item_id',
                'po_items.po_item_no as po_item_no',
                'po_items.qty',
                'po_items.po_rest_qty',
                'po_items.unit_price',
                'po_items.po_item_status',
                'po_items.price',
                'categories.id as category_id',  // Ensure you select the category ID
                'categories.name as category_name',
            )
            ->where('po_items.id', $id)
            ->first();
        // dd($po_data);


        $purchaseOrder = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->select('companies.company_name', 'purchase_orders.*')
            ->where('purchase_orders.id', $po_data->purchase_order_id)->first();
        // dd($purchaseOrder);


        if (!$purchaseOrder) {
            return response()->json(['error' => 'Purchase Order not found'], 404);
        }

        // dd($po_data);
        $categoryIds = is_array($po_data->category_id) ? $po_data->category_id : [$po_data->category_id]; // Ensure it's an array
        $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->join('so_items', 'so_items.so_id', '=', 'sales_orders.id')
            ->join('categories', 'categories.id', '=', 'so_items.item_category')
            ->where('sales_orders.match_position', 'open')
            ->whereIn('so_items.item_category', $categoryIds)  // Match category IDs
            ->select('sales_orders.*', 'companies.company_name', 'so_items.id as so_item_id', 'so_items.*', 'categories.name as category_name')
            ->get();



        $manual_match = PurchaseSellMatch::Join('purchase_orders', 'purchase_sell_match.po_id', '=', 'purchase_orders.id')
            ->Join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
            ->Join('sales_orders', 'purchase_sell_match.so_id', '=', 'sales_orders.id')
            ->Join('so_items', 'purchase_sell_match.so_item_id', '=', 'so_items.id')
            ->leftJoin('companies as so_companies', 'so_companies.id', '=', 'sales_orders.company_id')
            ->leftJoin('companies as po_companies', 'po_companies.id', '=', 'purchase_orders.supplier_id')
            ->leftJoin('categories as po_categories', 'po_categories.id', '=', 'po_items.item_category')
            ->leftJoin('categories as so_categories', 'so_categories.id', '=', 'so_items.item_category')
            ->select(
                'purchase_sell_match.id as transaction_id',
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
                'po_items.po_item_no',
                'po_items.unit_price as po_items_unit_price',
                'so_items.so_item_no',
                'so_items.unit_price as so_items_unit_price',
                'po_items.po_item_status',
                'so_items.so_item_status',
                'sales_orders.match_position as so_match_position',
                'purchase_sell_match.matched_quantity',
                'po_categories.name as po_category_name',
                'so_categories.name as so_category_name',
            )
            ->where('purchase_sell_match.po_item_id', $id)
            ->where('po_items.item_category', $categoryIds)
            ->get(); // Add this line to debug the raw SQL

        // dd($manual_match);


        // dd($manual_match);

        return view('manual_matching.match_purchase', compact('purchaseOrder', 'salesOrders', 'manual_match', 'po_data'));
    }



    public function storePurSellMatch(Request $request)
    {
        // dd($request);
        if ($request->selected_so_items) {
        } else {
            return redirect()->back()->with('msg', 'Please Select atleast one item');
        }
      $total_matched_qty = 0;

      foreach ($request->selected_so_items as $soItemId){
        $matchedQuantity = $request->input('matched_quantity.' . $soItemId, 0);
        $total_matched_qty +=  $matchedQuantity;
      }
    

      $POItemCheck = PoItem::find($request->po_item_id);

      if( $POItemCheck->po_rest_qty < $total_matched_qty){
        return redirect()->back()->with('msg', 'Your Selected qty is greater than PO Item Qty');
      }

        $purchaseOrderId = $request->input('purchase_order_id');
        $salesOrderId = $request->input('sales_order_id');

   

        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);


        if (!$purchaseOrder) {
            return redirect()->back()->with('error', 'Invalid Purchase Order');
        }


        foreach ($request->selected_so_items as $soItemId) {

            // dd($soItemId);

            $matchedQuantity = $request->input('matched_quantity.' . $soItemId, 0);
            $poItemId = $request->input('po_item_id');
            $salesOrderItem = SoItem::find($soItemId);
            $purchaseOrderItem = PoItem::find($poItemId);
            // dd( $salesOrderItem,  $purchaseOrderItem);
            if (!$salesOrderItem || !$purchaseOrderItem) {
                continue;
            }


            if (
                $matchedQuantity <= 0 ||
                $matchedQuantity > $salesOrderItem->so_rest_qty ||
                $matchedQuantity > $purchaseOrderItem->po_rest_qty
            ) {
                continue;
            }



            // $purchaseOrderItemExists = PurchaseSellMatch::where('po_item_id', $poItemId)
            // ->where('so_item_id', $soItemId)
            // ->exists();


            //  if (!$purchaseOrderItemExists) {
            //      return redirect()->back()->with('error', 'Sales Order Item ID: ' . $soItemId . ' does not exist in Purchase Order.');
            //  }


            $remainingSoItemQuantity = $salesOrderItem->so_rest_qty - $matchedQuantity;
            $remainingPoItemQuantity = $purchaseOrderItem->po_rest_qty - $matchedQuantity;
            // dd( $remainingSoItemQuantity, $remainingPoItemQuantity);


            $purchaseSellMatch = PurchaseSellMatch::create([
                'so_item_id' => $soItemId,
                'po_item_id' => $poItemId,

                'so_item_unit_price' =>  $salesOrderItem->unit_price,
                'po_item_unit_price' => $purchaseOrderItem->unit_price,

                'so_item_price' => $salesOrderItem->price,
                'po_item_price' => $purchaseOrderItem->price,


                'matched_quantity' => $matchedQuantity,
                'po_item_rest_quantity' => $remainingPoItemQuantity,
                'so_item_rest_quantity' => $remainingSoItemQuantity,
            ]);

            // $salesOrderItem->update(['so_rest_qty' => $remainingSoItemQuantity]);
            // $purchaseOrderItem->update(['po_rest_qty' => $remainingPoItemQuantity]);

            $purchaseSellMatch->update([
                'so_id' => $salesOrderItem->so_id,
                'po_id' => $purchaseOrderId,
                'so_item_qty' => $salesOrderItem->so_rest_qty,
                'po_item_qty' => $purchaseOrderItem->po_rest_qty,
            ]);

            $salesOrderItem = SoItem::where('id', $soItemId)->update(['so_rest_qty' => number_format($remainingSoItemQuantity, 3)]);
            $purchaseOrderItem = PoItem::where('id', $poItemId)->update(['po_rest_qty' => number_format($remainingPoItemQuantity, 3)]);

            $new_so_item  = SoItem::where('id', $soItemId)->first();
            $new_po_item  = PoItem::where('id', $poItemId)->first();


            if ($new_so_item->so_rest_qty == 0) {
                SoItem::where('id', $soItemId)->update(['so_item_status' => 'Close']);
            }
            if ($new_po_item->po_rest_qty == 0) {
                PoItem::where('id', $poItemId)->update(['po_item_status' => 'Close']);
            }

            // dd( $s, $d );
            // $salesOrderItem->update(['so_match_position' => $remainingSoItemQuantity > 0 ? 'open' : 'close']);
            // $purchaseOrderItem->update(['po_match_position' => $remainingPoItemQuantity > 0 ? 'open' : 'close']);
        }

        $sales_order = SoItem::where('so_id',  $salesOrderId)->where('so_item_status', 'Open')->first();
        $purchase_order = PoItem::where('po_id',  $purchaseOrderId)->where('po_item_status', 'Open')->first();
        // dd( $purchase_order);

        if ($sales_order) {
        } else {
            SalesOrder::where('id', $salesOrderId)->update(['match_position' => 'Close']);
        }
        if ($purchase_order) {
        } else {
            PurchaseOrder::where('id', $purchaseOrderId)->update(['match_position' => 'Close']);
        }



        return redirect()->route('manual.matching')->with('success', 'Selected Sales Order items have been matched successfully.');
    }



    public function storePurSellMatchBuyer(Request $request)
    {
        if ($request->selected_po_items) {
        } else {
            return redirect()->back()->with('msg', 'Please Select atleast one item');
        }
        
        $total_matched_qty = 0;

        foreach ($request->selected_po_items as $poItemId){
          $matchedQuantity = $request->input('matched_quantity.' . $poItemId, 0);
          $total_matched_qty +=  $matchedQuantity;
        }
      
  
        $SOItemCheck = SoItem::find($request->so_item_id);
  
        if( $SOItemCheck->so_rest_qty < $total_matched_qty){
          return redirect()->back()->with('msg', 'Your Selected qty is greater than SO Item Rest Qty');
        }

    
        $purchaseOrderId = $request->input('purchase_order_id');
        $salesOrderId = $request->input('sales_order_id');

        // dd($salesOrderId,  $purchaseOrderId);



        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
        // dd($purchaseOrder);

        if (!$purchaseOrder) {
            return redirect()->back()->with('error', 'Invalid Sales Order');
        }


        foreach ($request->selected_po_items as $poItemId) {

            $matchedQuantity = $request->input('matched_quantity.' . $poItemId, 0);
            $soItemId = $request->input('so_item_id');
            $salesOrderItem = SoItem::find($soItemId);
            $purchaseOrderItem = PoItem::find($poItemId);
            // dd( $salesOrderItem,  $purchaseOrderItem);
            if (!$salesOrderItem || !$purchaseOrderItem) {
                continue;
            }


            if (
                $matchedQuantity <= 0 ||
                $matchedQuantity > $salesOrderItem->so_rest_qty ||
                $matchedQuantity > $purchaseOrderItem->po_rest_qty
            ) {
                continue;
            }


            $remainingSoItemQuantity = $salesOrderItem->so_rest_qty - $matchedQuantity;
            $remainingPoItemQuantity = $purchaseOrderItem->po_rest_qty - $matchedQuantity;
            // dd( $remainingSoItemQuantity, $remainingPoItemQuantity);


            $PurchaseSell = PurchaseSellMatch::create([
                'so_item_id' => $soItemId,
                'po_item_id' =>  $purchaseOrderItem->po_id,

                'so_item_unit_price' =>  $salesOrderItem->unit_price,
                'po_item_unit_price' => $purchaseOrderItem->unit_price,

                'so_item_price' => $salesOrderItem->price,
                'po_item_price' => $purchaseOrderItem->price,


                'matched_quantity' => $matchedQuantity,
                'po_item_rest_quantity' => $remainingPoItemQuantity,
                'so_item_rest_quantity' => $remainingSoItemQuantity,
            ]);

            // $salesOrderItem->update(['so_rest_qty' => $remainingSoItemQuantity]);
            // $purchaseOrderItem->update(['po_rest_qty' => $remainingPoItemQuantity]);

            $PurchaseSell->update([
                'so_id' => $salesOrderId,
                'po_id' => $purchaseOrderId,
                'so_item_qty' => $salesOrderItem->so_rest_qty,
                'po_item_qty' => $purchaseOrderItem->po_rest_qty,
            ]);

            $salesOrderItem = SoItem::where('id', $soItemId)->update(['so_rest_qty' => number_format($remainingSoItemQuantity, 3)]);
            $purchaseOrderItem = PoItem::where('id', $poItemId)->update(['po_rest_qty' => number_format($remainingPoItemQuantity, 3)]);


            // dd( $s, $d );

            $new_so_item  = SoItem::where('id', $soItemId)->first();
            $new_po_item  = PoItem::where('id', $poItemId)->first();


            if ($new_so_item->so_rest_qty == 0) {
                SoItem::where('id', $soItemId)->update(['so_item_status' => 'Close']);
            }
            if ($new_po_item->po_rest_qty == 0) {
                PoItem::where('id', $poItemId)->update(['po_item_status' => 'Close']);
            }
            // $salesOrderItem->update(['so_match_position' => $remainingSoItemQuantity > 0 ? 'open' : 'close']);
            // $purchaseOrderItem->update(['po_match_position' => $remainingPoItemQuantity > 0 ? 'open' : 'close']);
        }


        return redirect()->route('manual.matching')->with('success', 'Selected Sales Order has been matched successfully with the selected Purchase Order.');
    }







    // public function view_all()
    // {
    //     $suppliers = Company::where('type', '=', 'supplier')->get();
    //     $companies = Company::where('type', '=', 'supplier')->get();
    //     $buyers = Company::where('type', '=', 'buyer')->get(); // Retrieve all companies
    //     $transactions = InventoryTransaction::all();
    //     $manual_match = PurchaseSellMatch::join('purchase_orders', 'purchase_sell_match.po_id', '=', 'purchase_orders.id')
    //         ->join('sales_orders', 'purchase_sell_match.so_id', '=', 'sales_orders.id')
    //         ->join('po_items', 'purchase_sell_match.po_item_id', '=', 'po_items.id')
    //         ->join('so_items', 'purchase_sell_match.so_item_id', '=', 'so_items.id')
    //         // Alias the companies table for sales orders
    //         ->join('companies as so_companies', 'so_companies.id', '=', 'sales_orders.company_id')
    //         // Alias the companies table for purchase orders (suppliers)
    //         ->join('companies as po_companies', 'po_companies.id', '=', 'purchase_orders.supplier_id')
    //         ->select(
    //             'purchase_sell_match.id as match_id',
    //             'purchase_sell_match.created_at',
    //             'purchase_sell_match.*',
    //             'purchase_orders.id as po_id',
    //             'purchase_orders.document_number as po_number',
    //             'po_companies.company_name as po_company_name',  // Aliased to po_companies
    //             'purchase_orders.match_position as po_match_position',
    //             'sales_orders.id as so_id',
    //             'sales_orders.so_number as so_number',
    //             'so_companies.company_name as so_company_name',  // Aliased to so_companies
    //             'sales_orders.match_position as so_match_position',
    //             'purchase_sell_match.matched_quantity',
    //             'purchase_sell_match.po_item_rest_quantity',
    //             'purchase_sell_match.so_item_rest_quantity',

    //         )
    //         ->get();


    //     // dd($manual_match);
    //     return view('manual_matching.view_all', compact('companies', 'transactions', 'buyers', 'suppliers', 'manual_match'));
    // }








    public function match_sales($id)
    {

        // dd($id);
        $so_data = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->join('so_items', 'so_items.so_id', '=', 'sales_orders.id')
            ->join('categories', 'categories.id', '=', 'so_items.item_category')
            ->select(
                'sales_orders.id as sales_order_id',
                'sales_orders.date as so_date',
                'companies.company_name as supplier_name',
                'sales_orders.so_number',
                'so_items.id as so_item_id',
                'so_items.so_item_no as so_item_no',
                'so_items.qty',
                'so_items.so_item_no',
                'so_items.so_rest_qty',
                'so_items.so_item_status',
                'so_items.unit_price',
                'so_items.price',
                'categories.id as category_id',  // Ensure you select the category ID
                'categories.name as category_name',
            )
            ->where('so_items.id', $id)
            ->first();


        // dd($so_data);
        $salesOrders = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->where('sales_orders.id', $so_data->sales_order_id)
            ->select('sales_orders.*', 'companies.company_name', 'sales_orders.id as so_id')
            ->first();

        // dd($salesOrders);

        $categoryIds = is_array($so_data->category_id) ? $so_data->category_id : [$so_data->category_id]; // Ensure it's an array

        // $purchaseOrders = PurchaseOrder::where('match_position', 'open') // Adjust this based on how you define 'open' orders
        //     ->select('purchase_orders.*') // Add any other necessary fields
        //     ->first();

        $purchaseOrders = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
            ->join('categories', 'categories.id', '=', 'po_items.item_category')
            ->where('purchase_orders.match_position', 'open')
            ->whereIn('po_items.item_category', $categoryIds)  // Match category IDs
            ->select('purchase_orders.*', 'companies.company_name', 'po_items.id as po_item_id', 'po_items.*', 'categories.name as category_name')
            ->get();

        // dd($purchaseOrders);

        $manual_match = PurchaseSellMatch::join('purchase_orders', 'purchase_sell_match.po_id', '=', 'purchase_orders.id')
            ->join('po_items', 'purchase_sell_match.po_item_id', '=', 'po_items.id')
            ->join('sales_orders', 'purchase_sell_match.so_id', '=', 'sales_orders.id')
            ->join('so_items', 'purchase_sell_match.so_item_id', '=', 'so_items.id')
            ->join('companies as so_companies', 'so_companies.id', '=', 'sales_orders.company_id')
            ->join('companies as po_companies', 'po_companies.id', '=', 'purchase_orders.supplier_id')
            ->join('categories as po_categories', 'po_categories.id', '=', 'po_items.item_category') // Join categories for Purchase Orders
            ->join('categories as so_categories', 'so_categories.id', '=', 'so_items.item_category') // Join categories for Sales Orders
           
            ->select(
                'purchase_sell_match.id as transaction_id',
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

                'po_items.po_item_status',
                'so_items.so_item_status',

                'po_items.unit_price as po_items_unit_price',
                'so_items.unit_price as so_items_unit_price',

                'sales_orders.match_position as so_match_position',
                'purchase_sell_match.matched_quantity',
                'po_categories.name as po_category_name', // Select Purchase Order category name
                'so_categories.name as so_category_name', // Select Sales Order category name
                'po_items.po_item_no',
                'so_items.so_item_no',
            )
            ->where('purchase_sell_match.so_item_id', $id)
            ->where('so_items.item_category', $categoryIds)
            ->get();

        // dd($manual_match);

        return view('manual_matching.match_sales', compact('salesOrders', 'purchaseOrders', 'manual_match', 'so_data', 'purchaseOrders'));
    }

    public function transaction_revert(Request $request)
    {
        $purchase_sell = PurchaseSellMatch::where('id', $request->transaction_id)->first();

        $actual_so_rest_qty = ($purchase_sell->so_item_qty - $purchase_sell->so_item_rest_quantity);
        $actual_po_rest_qty = ($purchase_sell->po_item_qty - $purchase_sell->po_item_rest_quantity);

        SoItem::where('id', $purchase_sell->so_item_id)
            ->increment('so_rest_qty', $actual_so_rest_qty);
        SoItem::where('id', $purchase_sell->so_item_id)
            ->update(['so_item_status' => 'Open']);

        PoItem::where('id', $purchase_sell->po_item_id)
            ->increment('po_rest_qty', $actual_po_rest_qty);
        PoItem::where('id', $purchase_sell->po_item_id)
            ->update(['po_item_status' => 'Open']);



        $purchase_sell->delete();

        return redirect()->back()->with('success');
    }
}
