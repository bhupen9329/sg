<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\PoReceivedQuantity;
use App\Models\PurchaseOrder;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $po_data = PurchaseOrder::join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
            ->join('categories', 'purchase_orders.category', '=', 'categories.id')
            ->join('subcategories', 'purchase_orders.sub_category_id', '=', 'subcategories.id')
            ->whereIn('purchase_orders.status', ['Open', 'Partial Received'])
            ->select('categories.*', 'subcategories.sub_category', 'companies.*', 'purchase_orders.*', 'purchase_orders.date as date', 'purchase_orders.created_at as po_created_at', 'purchase_orders.id as po_id')
            ->orderBy('purchase_orders.created_at', 'desc')
            ->get();

        // dd($po_data);
        // $balanced_qty = PurchaseOrder::select(
        //     'purchase_orders.id',
        //     'purchase_orders.quantity',
        //     DB::raw('IFNULL(SUM(po_received_quantity.received_quantity), 0) as total_received_quantity'),
        //     DB::raw('purchase_orders.quantity - IFNULL(SUM(po_received_quantity.received_quantity), 0) as balanced_qty')
        // )
        //     ->leftJoin('po_received_quantity', 'purchase_orders.id', '=', 'po_received_quantity.po_id')
        //     ->groupBy('purchase_orders.id', 'purchase_orders.quantity')
        //     // ->where('status','Partial Received')
        //     ->get();
        // dd($balanced_qty);
        // $data = $po_data->map(function ($po, $key) use ($balanced_qty) {
        $data = $po_data->map(function ($po, $key) {
            $createdDate = Carbon::parse($po->date);
            $status = strtolower($po->status);

            if ($status === 'partial closed' || $status === 'total closed') {
                // Check if po_status_changed_at is valid
                $statusChangedDate = Carbon::parse($po->status);
                $po->order_age = floor($createdDate->diffInDays($statusChangedDate));
            } else {
                // If status is not "partial closed" or "total closed", calculate the age up to now
                $po->order_age = floor($createdDate->diffInDays(Carbon::now()));
                // dd($po->order_age);
            }

            // $po->balanced_qty = $balanced_qty[$key]->balanced_qty ;

            return $po;
        });

        // dd($po_data);

        return view('purchase.index', ['data' => $data]);

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
        $data = [
            'po_id' => $po_id,
            'company' => $company,
            'sub_category' => $sub_category,
            'custom_due_date' => $custom_due_date,
        ];
        // dd($data);
        return view('purchase.create')->with($data);
    }

    public function store(Request $request)
    {
        // dd($request);
        // dd($request->company_id);


        // dd($po_id);
        $po_data = [
            'supplier_id' => $request->company_id,
            'document_number' => $request->po_id,
            'category' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'quantity' => $request->quantity,
            'rest_quantity' => $request->quantity,
            'price' => $request->price,
            'remark' => $request->remark,
            'date' => $request->date,
            'no_of_due_date' => $request->no_of_due_date,
            'due_date' => $request->due_date,
            'status' => 'Open',
        ];

        // dd($po_data);
        PurchaseOrder::create($po_data);
        return redirect()->route('purchase.index')->with('success', 'Purchase order created Successfully');
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
