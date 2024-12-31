<?php

namespace App\Http\Controllers;

use App\Livewire\Warehouse;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\StockItem;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\GstSetting;
use App\Models\QtItem;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\SoItem;
use App\Models\SubCategory;
use App\Models\InventoryTransaction;
use App\Models\WareHouseModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\ValuationController;
use App\Models\LifoTransactionUsedQty;
use App\Models\LifoTransactionStack;
use App\Models\LifoTransaction;

use App\Models\FifoTransactionUsedQty;
use App\Models\FifoTransactionStack;
use App\Models\FifoTransaction;


use App\Models\AverageTransactionUsedQty;
use App\Models\AverageTransactionStack;
use App\Models\AverageTransaction;
use App\Models\Dispatch;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:Sales-index', ['only' => ['index','show']]);
        $this->middleware('permission:Sales-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:Sales-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Sales-delete', ['only' => ['delete']]);
    }




    public function index()
    {

        $user = Auth::user();
        $roles = $user->getRoleNames();


        $sales_order_query = SalesOrder::join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->join('so_items', 'so_items.so_id', '=', 'sales_orders.id')
            ->join('categories', 'categories.id', '=', 'so_items.item_category')
            ->join('users', 'sales_orders.so_user_id', '=', 'users.id')
            ->whereNotIn('so_items.so_dispatch_item_status', ['Close', 'Pre Closed', 'Cancelled'])
            ->select(
                '*',
                'sales_orders.id as so_id',
                'so_items.id as so_item_id',
                'so_items.*',
                'categories.name as category_name',
                'users.*'
            )
            ->orderBy('sales_orders.date', 'asc');

        if ($roles->contains('Admin')) {
            $sales_order = $sales_order_query->get();
        } else {
            $sales_order = $sales_order_query->where('sales_orders.so_user_id', $user->id)->get();
        }

        $company = Company::where('type', '!=', 'supplier')->get();
        $supplier_company = Company::where('type', '!=', 'buyer')->get();


        $warehouse = WareHouseModel::all();
        $data = [
            'sales_order' => $sales_order,
            'company' => $company,
            'warehouse' => $warehouse,
            'supplier_company' => $supplier_company,
        ];

        // dd($sales_order);

        return view('sales.index')->with($data);
    }

    public function create($id)
    {

        $company = Company::where('id', $id)->first();
        $category = Category::all();
        $custom_due_date = CompanySetting::first();
        $gstsetting = GstSetting::all();
        $user = User::all();
        $data = [
            'company' => $company,
            'category' => $category,
            'gstsetting' => $gstsetting,
            'user' =>  $user,
            'custom_due_date' => $custom_due_date,
        ];
        // dd($data);
        return view('sales.create')->with($data);
    }



    public function store(Request $request, ValuationController $valuationcontroller)
    {
        $year = date('Y');
        $max_serial_number = SalesOrder::all()->max('so_number');
        $last_serial_number = substr($max_serial_number, -4);
        $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        $so_number = 'SO' . $year . $next_serial_number;

        $salesOrder = new SalesOrder();
        $salesOrder->company_id = $request->company_id;
        $salesOrder->address = $request->address;
        $salesOrder->date = $request->date;
        $salesOrder->due_date = $request->due_date;
        $salesOrder->terms_condition = $request->terms_condition;
        $salesOrder->total_quantity = $request->total_quantity;
        $salesOrder->total_amount = $request->total_amount;

        $salesOrder->total_price = $request->total_price;

        $salesOrder->rest_quantity = $request->total_quantity;
        $salesOrder->status = 'pending';
        $salesOrder->match_position = 'open';

        $salesOrder->so_number = $so_number;
        $salesOrder->so_user_id = $request->user_id;
        // $salesOrder->document_file = $request->document_file;
        $salesOrder->document_file = 'uploads/documents/sales/' . $request->so_number . '/' . $request->so_number . '.pdf';

        $salesOrder->save();
        $id = $salesOrder->id;


        if ($id) {
            for ($i = 0; $i < count($request->unit_price_); $i++) {
                $soItem = new SoItem();
                $soItem->item_category = $request->item_category[$i];
                $soItem->item_subcategory = $request->item_subcategory[$i] ?? 'N/A';
                $soItem->qty = $request->qty[$i];
                $soItem->so_rest_qty = $request->qty[$i];
                $soItem->so_dispatch_rest_qty = $request->qty[$i];
                $soItem->unit_price = $request->unit_price_[$i];
                $soItem->price = $request->price[$i];
                $itemSerial = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                $soItem->so_item_no = $salesOrder->so_number . '-' . $itemSerial;
                $soItem->so_id = $id;

                $soItem->save();
                $newSoItemId = $soItem->id;

                $categoryName = Category::find($soItem->item_category)->name;
                $companyName = Company::find($salesOrder->company_id)->company_name;
                // dd($subcategoryName);


                $inventoryTransaction = new InventoryTransaction();
                $inventoryTransaction->so_item_id = $newSoItemId;
                $inventoryTransaction->item_name = $categoryName;
                $inventoryTransaction->item_id = $soItem->item_category;
                $inventoryTransaction->transaction_type = 'sell';
                $inventoryTransaction->quantity = $soItem->qty;
                $inventoryTransaction->transaction_date =  $request->date;
                $inventoryTransaction->unit_price = $soItem->unit_price;
                $inventoryTransaction->company_name = $companyName;
                $inventoryTransaction->position = 'open';
                $inventoryTransaction->save();

                $inventoryTransactionId = $inventoryTransaction->id;
                $inventoryItemId = $inventoryTransaction->item_id;

                // $this->handleLifoTransaction($inventoryItemId, $inventoryTransactionId, $request->date, $valuationcontroller, $request);
                // $this->handleFifoTransaction($inventoryItemId, $inventoryTransactionId, $request->date, $valuationcontroller, $request);
                // $this->handleAverageTransaction($inventoryItemId, $inventoryTransactionId, $request->date, $valuationcontroller, $request);



            }
            return redirect()->route('sales.index')->with('success', 'Sales Orders Created Successfully');;
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
        $lifo_calculations = $valuationcontroller->lifoCalculation($inventoryItemId);


        if (isset($lifo_calculations['transaction_logs']) && is_array($lifo_calculations['transaction_logs'])) {

            $transaction = end($lifo_calculations['transaction_logs']);
        } else {
            $transaction = null;
        }
        // dd($transaction);


        // Create a new LifoTransaction record
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

        // dd($lifoTransaction);
        // Save the LIFO transaction
        $lifoTransaction->save();

        // Save the inventory stack
        foreach ($transaction['inventory_stack'] as $inventory) {
            $lifoTransactionChild = new LifoTransactionStack(); // Assuming you have a LifoTransactionChild model
            $lifoTransactionChild->lifo_transaction_id = $lifoTransaction->id; // Set the parent transaction ID
            $lifoTransactionChild->inventory_transaction_id = $inventoryTransactionId; // Set the parent transaction ID
            $lifoTransactionChild->lifo_transaction_stacks_bal_qty = $inventory['quantity'];
            $lifoTransactionChild->lifo_transaction_stacks_bal_unit_price = $inventory['unit_price'];
            $lifoTransactionChild->lifo_transaction_stacks_bal_value = $inventory['quantity'] * $inventory['unit_price'];
            $lifoTransactionChild->purchase_date = $request->date;

            // Save the child inventory stack data
            $lifoTransactionChild->save();
        }

        // Optional: If you have any details to save, you can do that similarly here
        if ($transaction['details'] && count($transaction['details']) > 0) {
            foreach ($transaction['details'] as $detail) {
                $lifoTransactionChild = new LifoTransactionUsedQty();
                $lifoTransactionChild->lifo_transaction_id = $lifoTransaction->id; // Set the parent transaction ID
                $lifoTransactionChild->inventory_transaction_id = $inventoryTransactionId; // Set the parent transaction ID
                $lifoTransactionChild->lifo_transaction_used_bal_qty = $detail['used_qty'];
                $lifoTransactionChild->lifo_transaction_used_bal_unit_price = $detail['unit_price'];
                $lifoTransactionChild->lifo_transaction_used_bal_value = $detail['used_qty'] * $detail['unit_price'];
                // Save the child record
                $lifoTransactionChild->save(); // This line was missing
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

    public function delete($id)
    {
        SoItem::where('id', $id)->delete();
        InventoryTransaction::where('so_item_id', $id)->delete();
        return redirect()->route('sales.index')->with('delete', 'Sales Orders Item Delete Successfully');
    }

    public function close(Request $request)
    {

        $data = [
            'status' => $request->so_close_type,
        ];
        SalesOrder::where('id', $request->id)->update($data);
        return redirect()->route('sales.index')->with('update', 'Sales Orders Updated Successfully');
    }

    public function edit($id)
    {
        $sales_order = SalesOrder::where('sales_orders.id', $id)
            ->select('sales_orders.*', 'sales_orders.id as so_id')
            ->first();
        $user = User::all();


        if ($sales_order->due_date != null) {
            $due_date = Carbon::parse($sales_order->due_date);
            $date = Carbon::parse($sales_order->date);

            $number_of_days = $date->diffInDays($due_date, false);
        }


        // dd($number_of_days);

        $company = Company::where('id', $sales_order->company_id)->first();
        $category = Category::all();
        $category_2 = Category::all();

        // dd( $category);
        $custom_due_date = CompanySetting::first();
        $gstsetting = GstSetting::all();
        $so_number = $sales_order->so_number;

        $so_items = SoItem::join('categories', 'so_items.item_category', '=', 'categories.id')->where('so_items.so_item_status', 'Open')->where('so_items.so_id', $id)->where('so_items.so_dispatch_item_status', 'Open')->select('categories.*', 'so_items.*', 'so_items.price as price')->get();
        //   dd( $so_items);
        $data = [
            'company' => $company,
            'category' => $category,
            'gstsetting' => $gstsetting,
            'so_number' => $so_number,
            'custom_due_date' => $custom_due_date,
            'number_of_days' => $number_of_days,
            'sales_order' => $sales_order,
            'so_items' => $so_items,
            'category_2' => $category_2,
            'user' =>  $user
        ];
        // dd($data);
        return view('sales.edit')->with($data);
    }

    public function update(Request $request, $id)
    {

        $sales_order = SalesOrder::where('id', $id)->first();

        $data = [
            'date' => $request->date,
            'due_date' => $request->due_date,
            'terms_condition' =>  $request->terms_condition,
            'total_quantity' => $request->total_quantity,
            'total_amount' => $request->total_amount,
            'total_price' => $request->total_price,
            'so_user_id' => $request->user_id,
        ];

        SalesOrder::where('id', $id)->update($data);
        $so_item = SoItem::where('so_id', $id)->whereColumn('qty', '=', 'so_rest_qty')->whereColumn('qty', '=', 'so_dispatch_rest_qty')->get();
        foreach ($so_item as $so_items) {
            InventoryTransaction::where('so_item_id', $so_items->id)->delete();
        }

        SoItem::where('so_id', $id)->whereColumn('qty', '=', 'so_rest_qty')->whereColumn('qty', '=', 'so_dispatch_rest_qty')->delete();

        if ($id) {
            if (isset($request->unit_price_) >  0) {
                for ($i = 0; $i < count($request->unit_price_); $i++) {
                    $soItem = new SoItem();
                    $soItem->item_category = $request->item_category[$i];
                    $soItem->item_subcategory = $request->item_subcategory[$i] ?? 'N/A';
                    $soItem->qty = $request->qty[$i];
                    $soItem->so_rest_qty = $request->qty[$i];
                    $soItem->so_dispatch_rest_qty = $request->qty[$i];
                    $soItem->unit_price = $request->unit_price_[$i];
                    $soItem->price = $request->price[$i];
                    $soItem->so_dispatch_rest_qty = $request->qty[$i];
                    $so_item_available = SoItem::where('so_id', $id)->latest()->first();

                    if ($so_item_available) {
                        // Extract the last two digits from `so_item_no` to get the current serial number
                        $lastSerialNumber = (int)substr($so_item_available->so_item_no, -2);
                        $itemSerial = str_pad($lastSerialNumber + 1, 2, '0', STR_PAD_LEFT);
                    } else {
                        // Start from '01' if no previous item exists
                        $itemSerial = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                    }
                    $soItem->so_item_no = $sales_order->so_number . '-' . $itemSerial;
                    $soItem->so_id = $id;

                    $soItem->save();
                    $newSoItemId = $soItem->id;
                    // dd($soItem->item_category);

                    $categoryName = Category::find($soItem->item_category)->name;


                    $companyName = Company::find($sales_order->company_id)->company_name;
                    // dd($subcategoryName);

                    $inventoryTransaction = new InventoryTransaction();
                    $inventoryTransaction->so_item_id = $newSoItemId;
                    $inventoryTransaction->item_name = $categoryName;
                    $inventoryTransaction->item_id = $soItem->item_category;
                    $inventoryTransaction->transaction_type = 'sell';
                    $inventoryTransaction->quantity = $soItem->qty;
                    $inventoryTransaction->transaction_date =  $request->date;
                    $inventoryTransaction->unit_price = $soItem->unit_price;
                    $inventoryTransaction->company_name = $companyName;
                    $inventoryTransaction->position = 'open';
                    $inventoryTransaction->save();
                }
            } else {
            }
            return redirect()->route('sales.index')->with('update', 'Sales Orders Updated Successfully');;
        }
    }

    public function show($id)
    {
        $sales_data = SalesOrder::where('id', $id)->first();
        $company_data = Company::where('id', $sales_data->company_id)->first();
        $so_item = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
            ->join('subcategories', 'so_items.item_subcategory', '=', 'subcategories.id')
            ->select(
                'so_items.*',
                'categories.name as name',
                'subcategories.sub_category as sub_category'
            )
            ->where('so_items.sale_id', $id)
            ->get();
        // dd($sales_data, $so_item);
        return view('sales.show', compact('sales_data', 'company_data', 'so_item'));
    }
    public function get_quotation_details($id)
    {
        $quotations = Quotation::where('company_id', $id)->where('status', 'pending')->get(['document_number']); // Sirf document_number field ko select karein
        $documentNumbers = $quotations->pluck('document_number');
        return response()->json([
            'data' => $documentNumbers,
        ]);
    }

    public function get_sales_details($id)
    {
        $sales_orders = SalesOrder::where('company_id', $id)
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere('status', 'partial pending');
            })
            ->get(['id', 'so_number', 'remarks']); // Select both id and so_number

        // Prepare the data array
        $data = $sales_orders->map(function ($order) {
            return [
                'so_id' => $order->id,
                'documentNumber' => $order->so_number,

            ];
        });
        // Return JSON response
        return response()->json([
            'data' => $data
        ]);
    }


    public function create_quotation(Request $request)
    {
        $year = date('Y');
        $max_serial_number = SalesOrder::all()->max('so_number');
        $last_serial_number = substr($max_serial_number, -4);
        $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        $next_document = 'SO' . $year . $next_serial_number;
        $quotations = Quotation::where('document_number', $request->quotation_number)->first();
        $company = Company::where('id', $request->company_id)->first();
        $category = Category::all();
        $gstsetting = GstSetting::all();

        $qt_item = QtItem::join('categories', 'categories.id', '=', 'qt_items.item_category')
            ->join('subcategories', 'subcategories.id', '=', 'qt_items.item_subcategory')
            ->select('*', 'qt_items.weight as qt_weight', 'qt_items.price as qt_price')
            ->where('qt_id', $quotations->id)->get();

        $data = [
            'company' => $company,
            'category' => $category,
            'so_type' => 'quotation',
            'gstsetting' => $gstsetting,
            'quotation' => $quotations,
            'next_document' => $next_document,
            'qt_item' => $qt_item,
        ];
        return view('sales.create_forquotation')->with($data);
    }
    public function store_quotation(Request $request)
    {
        // dd($request);
        $salesOrder = new SalesOrder();
        $salesOrder->qt_id = $request->qt_id;
        $salesOrder->company_id = $request->company_id;
        $salesOrder->address = $request->address;
        $salesOrder->date = $request->date;
        $salesOrder->payment_mandatory = $request->payment_mandatory;
        $salesOrder->total_weight = $request->total_weight;
        $salesOrder->total_pcs = $request->total_pcs;
        $salesOrder->status = 'pending';
        $salesOrder->sub_total = $request->sub_total;
        $salesOrder->total_sgst = $request->total_sgst;
        $salesOrder->total_cgst = $request->total_cgst;
        $salesOrder->total_igst = $request->total_igst;
        $salesOrder->additional_charges = $request->additional_charges;
        $salesOrder->loading_charges = $request->loading_cutting;
        $salesOrder->freight = $request->freight;
        $salesOrder->grand_total = $request->grand_total;
        $salesOrder->remarks = $request->remarks;
        $salesOrder->so_type = $request->so_type;
        $salesOrder->gst_type = $request->gst_type;
        $salesOrder->so_number = $request->document_number;
        $salesOrder->warehouse_id = $request->warehouse_id;
        $salesOrder->document_file = 'uploads/documents/sales/' . $request->document_number . '/' . $request->document_number . '.pdf';
        $salesOrder->save();
        $id = $salesOrder->id;

        if ($id) {
            // SO Item Code
            for ($i = 0; $i < count($request->amount); $i++) {
                $stock = StockItem::where('category_id', $request->category_id[$i])
                    ->where('sub_category_id', $request->subcategory_id[$i])
                    ->where('length', $request->length[$i])
                    ->where('warehouse_id', $request->warehouse_id)
                    ->first();

                if (!$stock) {
                    $stock_data = [
                        'category_id' => $request->category_id[$i],
                        'sub_category_id' => $request->subcategory_id[$i],
                        'length' => $request->length[$i],
                        'warehouse_id' => $request->warehouse_id,
                        'piece' => 0,
                        'weight' => 0,
                    ];
                    StockItem::create($stock_data);
                }

                $soItem = new SoItem();
                $soItem->item_category = $request->category_id[$i];
                $soItem->item_subcategory = $request->subcategory_id[$i];
                $soItem->qty = $request->qty[$i];
                $soItem->length = $request->length[$i];
                $soItem->uom_type = $request->uom_type[$i] ?? 'pcs';
                $soItem->price = $request->price[$i] ?? null;
                $soItem->pcs = $request->pcs[$i] ?? null;
                $soItem->rest_pcs = $request->pcs[$i] ?? null;
                $soItem->weight = $request->weight[$i] ?? null;
                $soItem->warehouse_id = $request->warehouse_id;
                $soItem->amount = $request->amount[$i] ?? null;
                $soItem->gst_percent = $request->gst_percent[$i] ?? null;
                $soItem->sgst = $request->sgst[$i] ?? 0; // Default to 0 if sgst is null
                $soItem->cgst = $request->cgst[$i] ?? 0; // Default to 0 if cgst is null
                $soItem->igst = $request->igst[$i] ?? 0; // Default to 0 if igst is null
                $soItem->sale_id = $id;
                $soItem->save();
            }

            Quotation::where('id', $request->qt_id)->update(['status' => 'sales generated']);


            // return redirect()->route('sales.pdf', $id);
            return redirect()->route('sales.pdf', $id);
        }
    }


    public function get_remark(Request $request)
    {
        $so_data = SalesOrder::where('id', $request->id)->first();
        if ($so_data) {
            $so_remark = $so_data->remarks;
        } else {
            $so_remark = '';
        }
        // dd($so_remark);
        return response([
            'remark' => $so_remark
        ]);
    }
    public function closeSales(Request $request)
    {
        // Validate the request
        $data = [
            'remarks' => $request->remarks,
            'status' => $request->so_close_type,
        ];
        // dd($data);
        SalesOrder::where('id', $request->id)->update($data);
        // Redirect or return a response
        return redirect()->back()->with('success', 'Sales order updated successfully.');
    }
    public function getSoRemarkForModal(Request $request)
    {
        $so_id = $request->so_id;
        $salesOrder = SalesOrder::find($so_id);
        return response()->json(['remark' => $salesOrder->remarks]);
    }

    public function get_received_qty_so(Request $request)
    {

        $So_data = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->join('companies', 'sales_orders.company_id', '=', 'companies.id')
            ->where('so_items.item_category', $request->get_category_id)
            ->where('so_items.so_dispatch_rest_qty', '!=', 0)
            ->get();

        return response()->json([
            'rows_data' => $So_data
        ]);
    }

    public function get_received_qty_so_party_wise(Request $request)
    {

        $so_data = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->join('companies', 'sales_orders.company_id', '=', 'companies.id')
            ->where('sales_orders.company_id', $request->company_id)
            ->select(
                'so_items.item_category',
                'categories.name as category_name',
                DB::raw('SUM(so_items.so_dispatch_rest_qty) as total_quantity')
            )
            ->groupBy('so_items.item_category', 'categories.name')
            ->where('so_items.so_dispatch_rest_qty', '!=', 0)
            ->get();


        return response()->json([
            'rows_data' => $so_data
        ]);
    }

    public function get_received_qty_po_party_wise(Request $request)
    {

        $po_data = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
            ->where('purchase_orders.supplier_id', $request->company_id)
            ->select(
                'po_items.item_category',
                'categories.name as category_name',
                DB::raw('SUM(po_items.po_dispatch_rest_qty) as total_quantity')
            )
            ->groupBy('po_items.item_category', 'categories.name')
            ->where('po_items.po_dispatch_rest_qty', '!=', 0)
            ->get();


        return response()->json([
            'rows_data' => $po_data
        ]);
    }

    
    public function so_pre_closed_save(Request $request){
        SoItem::where('id', $request->so_item_id)->update(['so_dispatch_item_status' => $request->status, 'so_item_status_date' => $request->date,  'so_item_status_remarks' => $request->remarks,]);
        return redirect()->route('sales.index')->with('success', 'Sales Order Status Updated Successfully');
    }


    public function get_dispatch_qty(Request $request)
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
        ->where('dispatches.so_item_id', $request->get_so_item_id)->get();

        return response()->json([
            'received_qty_records' => $received_qty_records,
            'total_dispatched' => $request->total_dispatched,

        ]);
    }
}
