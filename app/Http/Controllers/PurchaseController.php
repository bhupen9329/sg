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

use App\Models\LifoTransactionUsedQty;
use App\Models\LifoTransactionStack;
use App\Models\LifoTransaction;

use App\Models\FifoTransactionUsedQty;
use App\Models\FifoTransactionStack;
use App\Models\FifoTransaction;

use App\Models\AverageTransactionUsedQty;
use App\Models\AverageTransactionStack;
use App\Models\AverageTransaction;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Http\Controllers\ValuationController;

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
            ->join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
            ->join('categories', 'categories.id', '=', 'po_items.item_category')
            ->select('*', 'purchase_orders.id as po_id', 'po_items.*', 'categories.name as category_name')
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
        $category = Category::all();
        $data = [
            'po_id' => $po_id,
            'company' => $company,
            'category' => $category,
            'custom_due_date' => $custom_due_date,
        ];
        // dd($data);
        return view('purchase.create')->with($data);
    }

    public function store(Request $request, ValuationController $valuationcontroller)
    {
        //  dd($request);
        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->supplier_id = $request->company_id;
        $purchaseOrder->document_number = $request->po_id;
        $purchaseOrder->category = $request->category_id;
        $purchaseOrder->sub_category_id = $request->sub_category_id;
        $purchaseOrder->total_quantity = $request->total_quantity;
        $purchaseOrder->rest_quantity = $request->total_quantity;
        $purchaseOrder->total_price = $request->total_price;
        $purchaseOrder->total_amount = $request->total_amount;
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
                $poItem->item_subcategory = $request->item_subcategory[$i] ?? 'N/A';
                $poItem->qty = $request->qty[$i];
                $poItem->po_rest_qty = $request->qty[$i];
                $poItem->po_dispatch_rest_qty = $request->qty[$i];
                $poItem->unit_price = $request->unit_price_[$i];
                $poItem->price = $request->price[$i];

                // Generate item serial number (e.g., PO20240002-01, PO20240002-02)
                $itemSerial = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                $poItem->po_item_no = $purchaseOrder->document_number . '-' . $itemSerial;

                // Link the item to the Purchase Order
                $poItem->po_id = $po_id;

                // Save Purchase Order Item
                $poItem->save();

                $newPoItemId = $poItem->id;

                $categoryName = Category::find($poItem->item_category)->name;

                $companyName = Company::find($purchaseOrder->supplier_id)->company_name;
                // dd($subcategoryName);


                $inventoryTransaction = new InventoryTransaction();
                $inventoryTransaction->po_item_id =  $newPoItemId;
                $inventoryTransaction->item_name = $categoryName;
                $inventoryTransaction->item_id = $poItem->item_category;
                $inventoryTransaction->transaction_type = 'purchase';
                $inventoryTransaction->quantity = $poItem->qty;
                $inventoryTransaction->transaction_date = $request->date;
                $inventoryTransaction->unit_price = $poItem->unit_price;
                $inventoryTransaction->company_name = $companyName;
                $inventoryTransaction->position = 'open';
                $inventoryTransaction->save();

                $inventoryTransactionId = $inventoryTransaction->id;
                $inventoryItemId = $inventoryTransaction->item_id;

                $transactions  = InventoryTransaction::where('id', $inventoryTransactionId)->first();

                $last_lifo_transaction = LifoTransaction::latest()->first();
                if ($last_lifo_transaction) {
                    $last_stack = LifoTransactionStack::where('lifo_transaction_id', $last_lifo_transaction->id)->get();
                    $last_used = LifoTransactionUsedQty::where('lifo_transaction_id', $last_lifo_transaction->id)->get();

                    if( $last_lifo_transaction->stock_bal_value > 0){
                        $lifoTransaction = new LifoTransaction();
                        $lifoTransaction->inventory_transaction_id = $inventoryTransactionId;
                        $lifoTransaction->stock_bal_qty = $transactions->quantity + $last_lifo_transaction->stock_bal_qty;
                        $lifoTransaction->stock_bal_unit_price = $transactions->unit_price;
                        $lifoTransaction->stock_bal_value = ($transactions->unit_price *  $transactions->quantity);
                        $lifoTransaction->cogs_qty = 0;
                        $lifoTransaction->cogs_unit_price = 0;
                        $lifoTransaction->cogs_bal_value = 0;

                        $lifoTransaction->actual_sales_qty     = 0;
                        $lifoTransaction->actual_sales_unit_price = 0;
                        $lifoTransaction->actual_sales_value = 0;

                        $lifoTransaction->profit_loss = 0;
                        if ($lifoTransaction->profit_loss > 0) {
                            $lifoTransaction->status = 'Profit';
                        } elseif($lifoTransaction->profit_loss < 0) {
                            $lifoTransaction->status = 'Loss';
                        }else{
                            $lifoTransaction->status = 'N/A';
                        }

                        $lifoTransaction->item_id = $inventoryItemId;
                        $lifoTransaction->stock_position = 'Long';
                        $lifoTransaction->save();

                        $stack_data = [
                          'lifo_transaction_id' =>  $lifoTransaction->id,
                          'inventory_transaction_id' => $inventoryTransactionId,
                          'purchase_date' => $request->date,
                          'lifo_transaction_stacks_bal_qty' => $transactions->quantity,
                          'lifo_transaction_stacks_bal_unit_price' => $transactions->unit_price,
                          'lifo_transaction_stacks_bal_value' => ($transactions->unit_price *  $transactions->quantity)
                        ];

                        LifoTransactionStack::create($stack_data);
                    }
                } else {
                    if (strtolower($transactions->transaction_type) === 'purchase') {
                        $lifoTransaction = new LifoTransaction();
                        $lifoTransaction->inventory_transaction_id = $inventoryTransactionId;
                        $lifoTransaction->stock_bal_qty = $transactions->quantity;
                        $lifoTransaction->stock_bal_unit_price = $transactions->unit_price;
                        $lifoTransaction->stock_bal_value = ($transactions->unit_price *  $transactions->quantity);
                        $lifoTransaction->cogs_qty = 0;
                        $lifoTransaction->cogs_unit_price = 0;
                        $lifoTransaction->cogs_bal_value = 0;

                        $lifoTransaction->actual_sales_qty     = 0;
                        $lifoTransaction->actual_sales_unit_price = 0;
                        $lifoTransaction->actual_sales_value = 0;

                        $lifoTransaction->profit_loss = 0;
                        if ($lifoTransaction->profit_loss > 0) {
                            $lifoTransaction->status = 'Profit';
                        } elseif($lifoTransaction->profit_loss < 0) {
                            $lifoTransaction->status = 'Loss';
                        }else{
                            $lifoTransaction->status = 'N/A';
                        }

                        $lifoTransaction->item_id = $inventoryItemId;
                        $lifoTransaction->stock_position = 'Long';
                        $lifoTransaction->save();
                    
                    }
                }
            }

            // Redirect with success message
            return redirect()->route('purchase.index')->with('success', 'Purchase Order Created Successfully');
        }
    }

    private function handleAverageTransaction($inventoryItemId, $inventoryTransactionId, $transactionDate, $valuationcontroller, Request $request)
    {

        $average_calculations = $valuationcontroller->averageCalculation($inventoryItemId);
        // dd($lifo_calculations);


        if (isset($average_calculations['transaction_logs']) && is_array($average_calculations['transaction_logs'])) {

            $transaction = end($average_calculations['transaction_logs']);
        } else {
            $transaction = null;
        }

        $averageTransaction = new AverageTransaction();
        $averageTransaction->inventory_transaction_id = $transaction['transaction_id'];
        $averageTransaction->stock_bal_qty = $transaction['balance_qty'];;
        $averageTransaction->stock_bal_unit_price = $transaction['balance_unit_price'];
        $averageTransaction->stock_bal_value = $transaction['balance_value'];
        $averageTransaction->cogs_qty = $transaction['cost_of_goods_sold_qty'];
        $averageTransaction->cogs_unit_price = $transaction['cost_of_goods_sold_balance'];
        $averageTransaction->cogs_bal_value = $transaction['cost_of_goods_sold'];

        $averageTransaction->actual_sales_qty     = $transaction['actual_sale_qty'];
        $averageTransaction->actual_sales_unit_price = $transaction['actual_sale_balance_unit_price'];
        $averageTransaction->actual_sales_value = $transaction['actual_sale_value'];

        $averageTransaction->profit_loss = abs($transaction['actual_sale_value']) - abs($transaction['cost_of_goods_sold']);
        if ($averageTransaction->profit_loss > 0) {
            $averageTransaction->status = 'Profit';
        } else {
            $averageTransaction->status = 'Loss';
        }

        $averageTransaction->item_id = $transaction['item_id'];
        $averageTransaction->stock_position =  $transaction['status'];


        $averageTransaction->save();


        foreach ($transaction['inventory_stack'] as $inventory) {
            $averageTransactionChild = new AverageTransactionStack();
            $averageTransactionChild->average_transaction_id = $averageTransaction->id;
            $averageTransactionChild->inventory_transaction_id = $inventoryTransactionId;
            $averageTransactionChild->average_transaction_stacks_bal_qty = $inventory['quantity'];
            $averageTransactionChild->average_transaction_stacks_bal_unit_price = $inventory['unit_price'];
            $averageTransactionChild->average_transaction_stacks_bal_value = $inventory['quantity'] * $inventory['unit_price'];
            $averageTransactionChild->purchase_date = $request->date;


            $averageTransactionChild->save();
        }


        if ($transaction['details'] && count($transaction['details']) > 0) {
            foreach ($transaction['details'] as $detail) {
                $averageTransactionChild = new AverageTransactionUsedQty();
                $averageTransactionChild->lifo_transaction_id = $averageTransaction->id;
                $averageTransactionChild->inventory_transaction_id = $inventoryTransactionId;
                $averageTransactionChild->average_transaction_used_bal_qty = $detail['used_qty'];
                $averageTransactionChild->average_transaction_used_bal_unit_price = $detail['unit_price'];
                $averageTransactionChild->average_transaction_used_bal_value = $detail['used_qty'] * $detail['unit_price'];

                $averageTransactionChild->save();
            }
        }
    }



    private function handleLifoTransaction($inventoryItemId, $inventoryTransactionId, $transactionDate, $valuationcontroller, Request $request)
    {
        $lifo_calculations = $valuationcontroller->lifoCalculation($inventoryTransactionId);
        // dd($lifo_calculations);


        if (isset($lifo_calculations['transaction_logs']) && is_array($lifo_calculations['transaction_logs'])) {

            $transaction = end($lifo_calculations['transaction_logs']);
        } else {
            $transaction = null;
        }

        $lifoTransaction = new LifoTransaction();
        $lifoTransaction->inventory_transaction_id = $transaction['transaction_id'];
        $lifoTransaction->stock_bal_qty = $transaction['balance_qty'];;
        $lifoTransaction->stock_bal_unit_price = $transaction['balance_unit_price'];
        $lifoTransaction->stock_bal_value = $transaction['balance_value'];
        $lifoTransaction->cogs_qty = $transaction['cost_of_goods_sold_qty'];
        $lifoTransaction->cogs_unit_price = $transaction['cost_of_goods_sold_balance'];
        $lifoTransaction->cogs_bal_value = $transaction['cost_of_goods_sold'];

        $lifoTransaction->actual_sales_qty     = $transaction['actual_sale_qty'];
        $lifoTransaction->actual_sales_unit_price = $transaction['actual_sale_balance_unit_price'];
        $lifoTransaction->actual_sales_value = $transaction['actual_sale_value'];

        $lifoTransaction->profit_loss = abs($transaction['actual_sale_value']) - abs($transaction['cost_of_goods_sold']);
        if ($lifoTransaction->profit_loss > 0) {
            $lifoTransaction->status = 'Profit';
        } else {
            $lifoTransaction->status = 'Loss';
        }

        $lifoTransaction->item_id = $transaction['item_id'];
        $lifoTransaction->stock_position =  $transaction['status'];


        $lifoTransaction->save();


        foreach ($transaction['inventory_stack'] as $inventory) {
            $lifoTransactionChild = new LifoTransactionStack();
            $lifoTransactionChild->lifo_transaction_id = $lifoTransaction->id;
            $lifoTransactionChild->inventory_transaction_id = $inventoryTransactionId;
            $lifoTransactionChild->lifo_transaction_stacks_bal_qty = $inventory['quantity'];
            $lifoTransactionChild->lifo_transaction_stacks_bal_unit_price = $inventory['unit_price'];
            $lifoTransactionChild->lifo_transaction_stacks_bal_value = $inventory['quantity'] * $inventory['unit_price'];
            $lifoTransactionChild->purchase_date = $request->date;


            $lifoTransactionChild->save();
        }


        if ($transaction['details'] && count($transaction['details']) > 0) {
            foreach ($transaction['details'] as $detail) {
                $lifoTransactionChild = new LifoTransactionUsedQty();
                $lifoTransactionChild->lifo_transaction_id = $lifoTransaction->id;
                $lifoTransactionChild->inventory_transaction_id = $inventoryTransactionId;
                $lifoTransactionChild->lifo_transaction_used_bal_qty = $detail['used_qty'];
                $lifoTransactionChild->lifo_transaction_used_bal_unit_price = $detail['unit_price'];
                $lifoTransactionChild->lifo_transaction_used_bal_value = $detail['used_qty'] * $detail['unit_price'];

                $lifoTransactionChild->save();
            }
        }
    }



    private function handleFifoTransaction($inventoryItemId, $inventoryTransactionId, $transactionDate, $valuationcontroller, Request $request)
    {
        $fifo_calculations = $valuationcontroller->fifoCalculation($inventoryItemId);


        if (isset($fifo_calculations['transaction_logs']) && is_array($fifo_calculations['transaction_logs'])) {

            $transaction = end($fifo_calculations['transaction_logs']);
        } else {
            $transaction = null;
        }

        $fifoTransaction = new FifoTransaction();
        $fifoTransaction->inventory_transaction_id = $transaction['transaction_id'];
        $fifoTransaction->stock_bal_qty = $transaction['balance_qty'];;
        $fifoTransaction->stock_bal_unit_price = $transaction['balance_unit_price'];
        $fifoTransaction->stock_bal_value = $transaction['balance_value'];
        $fifoTransaction->cogs_qty = $transaction['cost_of_goods_sold_qty'];
        $fifoTransaction->cogs_unit_price = $transaction['cost_of_goods_sold_balance'];
        $fifoTransaction->cogs_bal_value = $transaction['cost_of_goods_sold'];

        $fifoTransaction->actual_sales_qty     = $transaction['actual_sale_qty'];
        $fifoTransaction->actual_sales_unit_price = $transaction['actual_sale_balance_unit_price'];
        $fifoTransaction->actual_sales_value = $transaction['actual_sale_value'];

        $fifoTransaction->profit_loss = abs($transaction['actual_sale_value']) - abs($transaction['cost_of_goods_sold']);
        if ($fifoTransaction->profit_loss > 0) {
            $fifoTransaction->status = 'Profit';
        } else {
            $fifoTransaction->status = 'Loss';
        }

        $fifoTransaction->item_id = $transaction['item_id'];
        $fifoTransaction->stock_position =  $transaction['status'];


        $fifoTransaction->save();


        foreach ($transaction['inventory_stack'] as $inventory) {
            $fifoTransactionChild = new FifoTransactionStack();
            $fifoTransactionChild->fifo_transaction_id = $fifoTransaction->id;
            $fifoTransactionChild->inventory_transaction_id = $inventoryTransactionId;
            $fifoTransactionChild->fifo_transaction_stacks_bal_qty = $inventory['quantity'];
            $fifoTransactionChild->fifo_transaction_stacks_bal_unit_price = $inventory['unit_price'];
            $fifoTransactionChild->fifo_transaction_stacks_bal_value = $inventory['quantity'] * $inventory['unit_price'];
            $fifoTransactionChild->purchase_date = $request->date;


            $fifoTransactionChild->save();
        }


        if ($transaction['details'] && count($transaction['details']) > 0) {
            foreach ($transaction['details'] as $detail) {
                $fifoTransactionChild = new FifoTransactionUsedQty();
                $fifoTransactionChild->fifo_transaction_id = $fifoTransaction->id;
                $fifoTransactionChild->inventory_transaction_id = $inventoryTransactionId;
                $fifoTransactionChild->fifo_transaction_used_bal_qty = $detail['used_qty'];
                $fifoTransactionChild->fifo_transaction_used_bal_unit_price = $detail['unit_price'];
                $fifoTransactionChild->fifo_transaction_used_bal_value = $detail['used_qty'] * $detail['unit_price'];

                $fifoTransactionChild->save();
            }
        }
    }




    public function edit($id)
    {
        // dd($id);
        $po_data = PurchaseOrder::where('id', $id)->first();

        $company = Company::where('id', $po_data->supplier_id)->first();
        $sub_category = SubCategory::all();
        $category = Category::all();
        $po_number = $po_data->document_number;

        $category_2 = Category::all();

        if ($po_data->due_date != null) {
            $due_date = Carbon::parse($po_data->due_date);
            $date = Carbon::parse($po_data->date);
            $number_of_days = $date->diffInDays($due_date, false);
        }

        $po_items = PoItem::join('categories', 'po_items.item_category', '=', 'categories.id')->where('po_items.po_id', $id)->where('po_items.po_item_status', 'Open')->where('po_items.po_dispatch_item_status', 'Open')->select('categories.*', 'po_items.*', 'po_items.price as price')->get();
        $data = [
            'company' => $company,
            'category' => $category,
            'po_number' => $po_number,
            'number_of_days' => $number_of_days,
            'po_data' => $po_data,
            'po_items' => $po_items,
            'category_2' => $category_2
        ];

        // dd($po_items);


        return view('purchase.edit')->with($data);
    }

    public function update(Request $request, $id)
    {


        $po_data = PurchaseOrder::where('id', $id)->first();

        $data = [
            'date' => $request->date,
            'due_date' => $request->due_date,
            'remark' =>  $request->remarks,
            'total_quantity' => $request->total_quantity,
            'total_amount' => $request->total_amount,
            'total_price' => $request->total_price,
        ];
        PurchaseOrder::where('id', $id)->update($data);

        $po_item = PoItem::where('po_id', $id)
            ->whereColumn('qty', '=', 'po_rest_qty')->get();

        foreach ($po_item as $po_items) {
            InventoryTransaction::where('po_item_id', $po_items->id)->delete();
        }

        PoItem::where('po_id', $id)
            ->whereColumn('qty', '=', 'po_rest_qty')
            ->delete();


        if ($id) {
            // Loop through each item for Purchase Order
            // dd($request);
            if (isset($request->unit_price_) >  0) {
                for ($i = 0; $i < count($request->unit_price_); $i++) {
                    $poItem = new PoItem();
                    $poItem->item_category = $request->item_category[$i];
                    $poItem->item_subcategory = $request->item_subcategory[$i] ?? 'N/A';
                    $poItem->qty = $request->qty[$i];
                    $poItem->po_rest_qty = $request->qty[$i];
                    $poItem->po_dispatch_rest_qty = $request->qty[$i];
                    $poItem->unit_price = $request->unit_price_[$i];
                    $poItem->price = $request->price[$i];
                    $poItem->po_dispatch_rest_qty = $request->qty[$i];

                    $po_item_available = PoItem::where('po_id', $id)->latest()->first();

                    if ($po_item_available) {
                        $lastSerialNumber = (int)substr($po_item_available->po_item_no, -2);
                        $itemSerial = str_pad($lastSerialNumber + 1, 2, '0', STR_PAD_LEFT);
                    } else {
                        $itemSerial = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                    }
                    $poItem->po_item_no = $po_data->document_number . '-' . $itemSerial;
                    $poItem->po_id = $id;
                    $poItem->save();

                    $newPoItemId = $poItem->id;

                    $categoryName = Category::find($poItem->item_category)->name;


                    $companyName = Company::find($po_data->supplier_id)->company_name;
                    // dd($subcategoryName);


                    $inventoryTransaction = new InventoryTransaction();
                    $inventoryTransaction->po_item_id =  $newPoItemId;
                    $inventoryTransaction->item_name = $categoryName;
                    $inventoryTransaction->item_id = $poItem->item_category;
                    $inventoryTransaction->transaction_type = 'purchase';
                    $inventoryTransaction->quantity = $poItem->qty;
                    $inventoryTransaction->transaction_date = $request->date;
                    $inventoryTransaction->unit_price = $poItem->unit_price;
                    $inventoryTransaction->company_name = $companyName;
                    $inventoryTransaction->position = 'open';
                    $inventoryTransaction->save();
                }
            } else {
            }


            // Redirect with success message
            return redirect()->route('purchase.index')->with('update', 'Purchase Order Updated Successfully');
        }
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
        return redirect()->route('purchase.index')->with('delete', 'Purchase order Deleted Successfully');;
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
        return redirect()->route('purchase.index')->with('Total_closed', 'Total closed Successfully');;
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
        // dd($request);

        $Po_data = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
        ->join('categories', 'po_items.item_category', '=', 'categories.id')
        ->join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
        ->where('po_items.item_category',$request->get_category_id)
        ->get();

        return response()->json([
            'rows_data' => $Po_data
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
