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
use App\Models\Dispatch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class PurchaseController extends Controller
{



    function __construct()
    {
        $this->middleware('permission:Purchase-index', ['only' => ['index']]);
        $this->middleware('permission:Purchase-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:Purchase-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Purchase-delete', ['only' => ['delete']]);
    }
    public function index()
    {

        $user = Auth::user(); // Get the authenticated user
        // Retrieve all roles assigned to the user
        $roles = $user->getRoleNames();

        if ($roles->contains('Admin')) {
            $po_data = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
                ->join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
                ->join('categories', 'categories.id', '=', 'po_items.item_category')
                ->join('users', 'purchase_orders.po_user_id', '=', 'users.id')
                ->select('*', 'purchase_orders.id as po_id', 'po_items.*', 'po_items.id as po_item_id', 'categories.name as category_name', 'users.*')
                ->whereNotIn('po_items.po_dispatch_item_status',  ['Close', 'Pre Closed', 'Cancelled'])
                ->orderBy('purchase_orders.date', 'asc')
                ->get();

            // dd( $po_data);
        } else {
            // Non-admin users ke liye sirf unhi ke PO dikhayein
            $po_data = PurchaseOrder::join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
                ->join('po_items', 'po_items.po_id', '=', 'purchase_orders.id')
                ->join('categories', 'categories.id', '=', 'po_items.item_category')
                ->join('users', 'purchase_orders.po_user_id', '=', 'users.id')
                ->select('*', 'purchase_orders.id as po_id', 'po_items.*', 'po_items.id as po_item_id', 'categories.name as category_name', 'users.*')
                ->whereNotIn('po_items.po_dispatch_item_status',  ['Close', 'Pre Closed', 'Cancelled'])
                ->where('purchase_orders.po_user_id', $user->id)
                ->orderBy('purchase_orders.date', 'asc')
                ->get();
        }

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

    public function create($id)
    {

        $company = Company::where('id', $id)->first();
        $custom_due_date = CompanySetting::first();
        $category = Category::all();
        // $user = User::all();
        $user = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin', 'Sales Person']);
        })->get();
        $data = [
            'company' => $company,
            'category' => $category,
            'custom_due_date' => $custom_due_date,
            'user' =>  $user,
        ];
        // dd($data);
        return view('purchase.create')->with($data);
    }

    public function store(Request $request, ValuationController $valuationcontroller)
    {
        //  dd($request);

        // $max_serial_number = PurchaseOrder::orderBy('document_number', 'desc')->first();
        // $year = date('Y');
        // if ($max_serial_number) {
        //     $max_serial_number = $max_serial_number->document_number;
        //     $last_serial_number = substr($max_serial_number, -4);
        //     $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        // } else {
        //     // Default serial number when no records exist
        //     $next_serial_number = str_pad(1, 4, '0', STR_PAD_LEFT);
        // }
        // $po_id = 'PO' . $year . $next_serial_number;

        $year = date('Y');
        $month = date('m');

        // Financial year calculation
        if ($month >= 4) {
            $financial_year_start = $year;
            $financial_year_end = $year + 1;
        } else {
            $financial_year_start = $year - 1;
            $financial_year_end = $year;
        }

        // Financial year format like 2024-2025
        $financial_year = $financial_year_start;

        // Fetch the latest document number for the current financial year
        $max_serial_number = PurchaseOrder::whereBetween('created_at', [
            "$financial_year_start-04-01 00:00:00",
            "$financial_year_end-03-31 23:59:59",
        ])->orderBy('document_number', 'desc')->first();

        if ($max_serial_number) {
            $last_serial_number = substr($max_serial_number->document_number, -4);
            $next_serial_number = str_pad((int)$last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Default serial number when no records exist
            $next_serial_number = str_pad(1, 4, '0', STR_PAD_LEFT);
        }

        // Generate PO number with financial year
        $po_id = 'PO' . $financial_year . $next_serial_number;





        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->supplier_id = $request->company_id;
        $purchaseOrder->document_number = $po_id;
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
        $purchaseOrder->po_user_id = $request->user_id;


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

                    if ($last_lifo_transaction->stock_bal_value > 0) {
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
                        } elseif ($lifoTransaction->profit_loss < 0) {
                            $lifoTransaction->status = 'Loss';
                        } else {
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
                        } elseif ($lifoTransaction->profit_loss < 0) {
                            $lifoTransaction->status = 'Loss';
                        } else {
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
        // $user = User::all();
        $user = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin', 'Sales Person']);
        })->get();

        if ($po_data->due_date != null) {
            $due_date = Carbon::parse($po_data->due_date);
            $date = Carbon::parse($po_data->date);
            $number_of_days = $date->diffInDays($due_date, false);
        }

        $po_items = PoItem::join('categories', 'po_items.item_category', '=', 'categories.id')->where('po_items.po_id', $id)->select('categories.*', 'po_items.*', 'po_items.price as price')->get();
        $data = [
            'company' => $company,
            'category' => $category,
            'po_number' => $po_number,
            'number_of_days' => $number_of_days,
            'po_data' => $po_data,
            'po_items' => $po_items,
            'category_2' => $category_2,
            'user' =>  $user,
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
            'remark' =>  $request->remark,
            'total_quantity' => $request->total_quantity,
            'total_amount' => $request->total_amount,
            'total_price' => $request->total_price,
            'po_user_id' => $request->user_id,
        ];
        PurchaseOrder::where('id', $id)->update($data);

        $po_item = PoItem::where('po_id', $id)
            ->whereColumn('qty', '=', 'po_rest_qty')->whereColumn('qty', '=', 'po_dispatch_rest_qty')->get();

        foreach ($po_item as $po_items) {
            InventoryTransaction::where('po_item_id', $po_items->id)->delete();
        }

        PoItem::where('po_id', $id)
            ->whereColumn('qty', '=', 'po_rest_qty')
            ->whereColumn('qty', '=', 'po_dispatch_rest_qty')
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
        PoItem::where('id', $id)->delete();
        InventoryTransaction::where('po_item_id', $id)->delete();
        return redirect()->route('purchase.index')->with('delete', 'Purchase Order Item Delete Successfully');
    }

    public function po_pre_closed_save(Request $request)
    {
        PoItem::where('id', $request->po_item_id)->update(['po_dispatch_item_status' => $request->status, 'po_item_status_date' => $request->date,  'po_item_status_remarks' => $request->remarks,]);
        return redirect()->route('purchase.index')->with('success', 'Purchase Order Status Updated Successfully');
    }
    public function get_received_qty(Request $request)
    {
        // dd($request);

        $Po_data = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
            ->where('po_items.item_category', $request->get_category_id)
            ->get();

        return response()->json([
            'rows_data' => $Po_data
        ]);
    }


    public function get_dispatch_qty_po(Request $request)
    {
        $received_qty_records = Dispatch::leftjoin('so_items', 'dispatches.so_item_id', '=', 'so_items.id')
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
                'po_items.unit_price as po_unit_price',
                'so_items.unit_price as so_unit_price',
                'po_items.qty as po_qty',
                'so_items.qty as so_qty',
                'dispatches.id as dispatch_id',
                'dispatches.date as dispatch_date',
            )
            ->where('dispatches.po_item_id', $request->get_po_item_id)->get();

        return response()->json([
            'received_qty_records' => $received_qty_records,
            'total_dispatched' => $request->total_dispatched,

        ]);
    }
}
