<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\PoReceivedQuantity;
use App\Models\PurchaseOrder;
use App\Models\SubCategory;
use App\Models\PoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryTransaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PurchaseController extends Controller
{



    function __construct()
    {
        $this->middleware('permission:Purchase-index', ['only' => ['index']]);
        $this->middleware('permission:Purchase-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:Purchase-view', ['only' => ['edit']]);
        $this->middleware('permission:Purchase-edit', ['only' => ['update']]);
        $this->middleware('permission:Purchase-close', ['only' => ['partial_receive_save']]);
        $this->middleware('permission:Purchase-delete', ['only' => ['delete']]);


    }
    public function index()
    {



        $po_data = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
        ->join('po_items','po_items.po_id','=','purchase_orders.id')
        ->join('categories','categories.id','=','po_items.item_category')
        ->join('subcategories','subcategories.id','=','po_items.item_subcategory')
        ->select('*', 'purchase_orders.id as id','po_items.*','categories.name as category_name','subcategories.sub_category as sub_category_name')
        ->get();
    
// dd($po_data);

        // $data = $po_data->map(function ($po, $key) {
        //     $createdDate = Carbon::parse($po->date);
        //     $status = strtolower($po->status);

        //     if ($status === 'partial closed' || $status === 'total closed') {
        //         // Check if po_status_changed_at is valid
        //         $statusChangedDate = Carbon::parse($po->status);
        //         $po->order_age = floor($createdDate->diffInDays($statusChangedDate));
        //     } else {
        //         // If status is not "partial closed" or "total closed", calculate the age up to now
        //         $po->order_age = floor($createdDate->diffInDays(Carbon::now()));
        //         // dd($po->order_age);
        //     }

        //     // $po->balanced_qty = $balanced_qty[$key]->balanced_qty ;

        //     return $po;
        // });

        // dd($po_data);

        return view('purchase.index', compact('po_data'));

    }

    public function create(Request $request)
    {
        $max_serial_number = PurchaseOrder::orderBy('document_number', 'desc')->first();
        $year = date('Y');
        if ($max_serial_number) {
            $max_serial_number = $max_serial_number->document_number;
            $last_serial_number = substr($max_serial_number, -4);
            $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Default serial number when no records exist
            $next_serial_number = str_pad(1, 4, '0', STR_PAD_LEFT);
        }
        $po_id = 'PO' . $year . $next_serial_number;
        // dd($po_id);

        $company = Company::where('id', $request->company_id)->first();
        $custom_due_date = CompanySetting::first();
        $sub_category = SubCategory::all();
        $category = Category::all();
        $data = [
            'po_id' => $po_id,
            'company' => $company,
            'category' => $category,
            'sub_category' => $sub_category,
            'custom_due_date' => $custom_due_date,
        ];
        // dd($data);
        return view('purchase.create')->with($data);
    }

    public function store(Request $request)
    {
    //  dd($request);
        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->supplier_id = $request->company_id;
        $purchaseOrder->document_number = $request->po_id;
        $purchaseOrder->category = $request->category_id;
        $purchaseOrder->sub_category_id = $request->sub_category_id;
        $purchaseOrder->quantity = $request->total_quantity;
        $purchaseOrder->rest_quantity = $request->total_quantity;
        $purchaseOrder->price = $request->total_price;
        $purchaseOrder->remark = $request->remark;
        $purchaseOrder->date = $request->date;
        $purchaseOrder->no_of_due_date = $request->no_of_due_date;
        $purchaseOrder->due_date = $request->due_date;
        $purchaseOrder->status = 'Open';
        $purchaseOrder->match_position = 'open';
    
        // Save Purchase Order
        $purchaseOrder->save();
        $po_id = $purchaseOrder->id;
        
        if ($po_id) {
            // Loop through each item for Purchase Order
            // dd($request);
            for ($i = 0; $i < count($request->unit_price_); $i++) {
                $poItem = new PoItem();
                $poItem->item_category = $request->item_category[$i];
                $poItem->item_subcategory = $request->item_subcategory[$i];
                $poItem->qty = $request->qty[$i];
                $poItem->po_rest_qty = $request->qty[$i];
                $poItem->unit_price = $request->unit_price_[$i];
                $poItem->price = $request->price[$i];
    
                // Generate item serial number (e.g., PO20240002-01, PO20240002-02)
                $itemSerial = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                $poItem->po_item_no = $purchaseOrder->document_number . '-' . $itemSerial;
    
                // Link the item to the Purchase Order
                $poItem->po_id = $po_id;
    
                // Save Purchase Order Item
                $poItem->save();

                $categoryName = Category::find($poItem->item_category)->name; 

                $subcategoryName = Subcategory::find($poItem->item_subcategory)->sub_category; 
                $companyName = Company::find($purchaseOrder->supplier_id)->company_name; 
                // dd($subcategoryName);

              
                $inventoryTransaction = new InventoryTransaction();
                $inventoryTransaction->item_name = $categoryName . ' - ' . $subcategoryName; 
                $inventoryTransaction->transaction_type = 'purchase'; 
                $inventoryTransaction->quantity = $poItem->qty;
                $inventoryTransaction->transaction_date = now(); 
                $inventoryTransaction->unit_price = $poItem->unit_price; 
                $inventoryTransaction->company_name = $companyName;
                $inventoryTransaction->position = 'open';
                $inventoryTransaction->save();
            }
    
            // Redirect with success message
            return redirect()->route('purchase.index')->with('success', 'Purchase Order Created Successfully');
        }
    }
    

    public function edit($id)
    {
        // dd($id);
        $po_data = PurchaseOrder::where('id', $id)->first();
        $PoReceivedQuantity = PoReceivedQuantity::where('po_id', $id)->sum('received_quantity');
        $balance_qty = $po_data->quantity - $PoReceivedQuantity;

        // dd($balance_qty);
        $selected_category = Category::find($po_data->category);
        $selected_sub_category = SubCategory::find($po_data->sub_category_id);
        $category = Category::all();
        // dd($category);
        $company = Company::where('id', $po_data->supplier_id)->first();

        // dd($po_data , $company);

        return view('purchase.edit', compact('po_data', 'company', 'category', 'selected_category', 'selected_sub_category', 'balance_qty'));
    }

    public function update(Request $request)
    {

        // dd($request);
        $po_data = [
            'supplier_id' => $request->company_id,
            'document_number' => $request->po_id,
            'category' => $request->category_id,
            'quantity' => $request->quantity,
            'rest_quantity' => $request->quantity,
            'price' => $request->price,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'no_of_due_date' => $request->no_of_due_date,
            'remark' => $request->remark,
        ];

        // dd($request->po_id);
        // dd($po_data);
        PurchaseOrder::where('document_number', $request->po_id)->update($po_data);
        return redirect()->route('purchase.index')->with('update', 'Purchase order Updated Successfully');
        ;
    }

    public function show($id)
    {
        $po_data = PurchaseOrder::join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
            ->select('companies.*', 'purchase_orders.*', 'purchase_orders.created_at as po_created_at', 'purchase_orders.id as po_id')->where('purchase_orders.id', $id)->first();
        // dd($po_data);
        return view('purchase.show', compact('po_data'));
    }
    public function delete($id)
    {
        PurchaseOrder::where('id', $id)->delete();
        PoReceivedQuantity::where('po_id', $id)->delete();
        return redirect()->route('purchase.index')->with('delete', 'Purchase order Deleted Successfully');
        ;
    }

    public function partial_receive_save(Request $request)
    {
        // dd($request);
        $po_id = $request->po_id;
        $received_quantity = $request->received_quantity;

        $received_qty = (int) $received_quantity;
        PurchaseOrder::where('id', $po_id)->update(['status' => 'Partial Received']);

        $po_rest_qty = PurchaseOrder::where('id', $po_id)->first();

        $rest_qty = $po_rest_qty->rest_quantity - $received_qty;

        PurchaseOrder::where('id', $po_id)->update(['rest_quantity' => $rest_qty]);

        // dd($rest_qty);


        $data = [
            'po_id' => $request->po_id,
            'received_quantity' => $request->received_quantity,
        ];
        // dd($data);

        PoReceivedQuantity::create($data);

        return redirect()->route('purchase.index')->with('Partial_created', 'Partial created Successfully');

    }
    public function partial_closed_save(Request $request)
    {

        // dd($request);
        $po_id = $request->po_id;
        $received_qty = (int) $request->received_quantity;
        // Update the PurchaseOrder
        PurchaseOrder::where('id', $po_id)->update(['status' => 'Partial Closed']);

        $po_rest_qty = PurchaseOrder::where('id', $po_id)->first();
        $rest_qty = $po_rest_qty->rest_quantity - $received_qty;

        PurchaseOrder::where('id', $po_id)->update(['rest_quantity' => $rest_qty]);

        $data = [
            'po_id' => $request->po_id,
            'received_quantity' => $request->received_quantity,
        ];
        PoReceivedQuantity::create($data);

        return redirect()->route('purchase.index')->with('Partial_closed', 'Partial closed Successfully');

    }
    public function total_closed(Request $request)
    {
        // dd($request);
        $for_po = [
            'status' => 'Total Closed',
            'close_date' => $request->closed_date,
        ];
        // dd($for_po);

        PurchaseOrder::where('id', $request->po_id)->update($for_po);
        return redirect()->route('purchase.index')->with('Total_closed', 'Total closed Successfully');
        ;
    }

    public function get_received_quantity(Request $request)
    {
        $total_qty = PurchaseOrder::where('id', $request->po_id)->select('rest_quantity')->first();
        return response([
            'total_qty' => $total_qty,
        ]);
    }
    public function get_received_qty(Request $request)
    {
        $received_qty_records = PoReceivedQuantity::where('po_id', $request->po_id)->get();
        $total_qty = PurchaseOrder::where('id', $request->po_id)->value('quantity');

        $balance_qty = $total_qty; // Initialize balance quantity with the total quantity
        $previous_received_qty_sum = 0; // Initialize sum of previous received quantities

        $rows_data = []; // Array to store data for each row

        foreach ($received_qty_records as $received_qty_record) {
            $received_qty = $received_qty_record->received_quantity;

            // Calculate balance quantity for the current row
            $balance_qty -= $received_qty;
            // Update the sum of previous received quantities for the next iteration
            $previous_received_qty_sum += $received_qty;

            // Store data for the current row
            $row_data = [
                'id' => $received_qty_record->id,
                'date' => $received_qty_record->created_at,
                'received_qty' => $received_qty,
                'balance_qty' => $balance_qty,
            ];

            // Add row data to the rows_data array
            $rows_data[] = $row_data;
        }

        return response()->json([
            'total_qty' => $total_qty,
            'rows_data' => $rows_data
        ]);
    }


    public function update_partial_received_quantity(Request $request)
    {
        // dd($request);
        $re_data = PoReceivedQuantity::where('po_id', $request->po_id)->sum('received_quantity');

        PoReceivedQuantity::where('id', $request->po_received_id)->update(['received_quantity' => $request->received_quantity]);

        $recheck_data = PoReceivedQuantity::where('po_id', $request->po_id)->sum('received_quantity');

        // dd($recheck_data);
        $overall_data = $recheck_data - $re_data;
        // dd($overall_data); 

        $po_data = PurchaseOrder::where('id', $request->po_id)->first();


        // dd($po_data);

        if ($po_data) {
            $po_data->rest_quantity = (int) $po_data->rest_quantity;
            $overall_data = (int) $overall_data;
            $po_data->rest_quantity -= $overall_data;
            $po_data->save();
        } else {
            return redirect()->route('purchase.index')->withErrors('Purchase order not found.');
        }

        return redirect()->route('purchase.index')->with('success', 'Received quantity updated successfully.');
    }


}
