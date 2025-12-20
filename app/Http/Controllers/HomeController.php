<?php

namespace App\Http\Controllers;

use App\Models\BasePrice;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\InventoryTransaction;
use App\Models\Inward;
use App\Models\MyNote;
use App\Models\Outward;
use App\Models\PoItem;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\SoItem;
use App\Models\StockItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ValuationController;


class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:Dashboard', ['only' => ['dashboard_index']]);

    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard_index()
    {
        $user_count = User::count();
        $role_count = Role::count();

        $sales_order = SoItem::whereNotNull('so_dispatch_item_status')
            ->whereNotIn('so_dispatch_item_status', ['Pre Closed', 'Close', 'Cancelled'])
            ->sum('so_dispatch_rest_qty');

        $purchase_order = PoItem::whereNotNull('po_dispatch_item_status')
            ->whereNotIn('po_dispatch_item_status', ['Pre Closed', 'Close', 'Cancelled'])
            ->sum('po_dispatch_rest_qty');

        // dd($purchase_order);

        $company_count = Company::all()->count();
        $my_notes = MyNote::latest()->first();
        $base_price = Category::get();
        $CompanySetting_data = CompanySetting::first();
        $threshold_value = (int)$CompanySetting_data->threshold_value;

        $categories = Category::all();
        $inventory_transaction = InventoryTransaction::orderBy('transaction_date', 'asc')->get();

        $lifo_transaction = [];
        $fifo_transaction = [];
        $avg_transaction = [];


        $latestEntriesByDate = [];

        $lifoData = '';
        $fifoData = '';
        $avgData = '';

        // Instantiate the ValuationController
        $valuationController = app(ValuationController::class);

        // foreach ($inventory_transaction as $data) {
        //     // Process LIFO, FIFO, and Average calculations using the ValuationController methods
        //     $lifoData = $valuationController->calculateLIFO($data->id, $data->item_id);
        //     $fifoData = $valuationController->calculateFIFO($data->id, $data->item_id);
        //     $avgData = $valuationController->calculateAverage($data->id, $data->item_id);

        //     // Get the latest transaction logs for each valuation method
        //     if (isset($lifoData['transaction_logs']) && is_array($lifoData['transaction_logs'])) {
        //         $latestEntriesByDate[$data->item_id]['lifo'] = end($lifoData['transaction_logs']);
        //     }
        //     if (isset($fifoData['transaction_logs']) && is_array($fifoData['transaction_logs'])) {
        //         $latestEntriesByDate[$data->item_id]['fifo'] = end($fifoData['transaction_logs']);
        //     }
        //     if (isset($avgData['transaction_logs']) && is_array($avgData['transaction_logs'])) {
        //         $latestEntriesByDate[$data->item_id]['avg'] = end($avgData['transaction_logs']);
        //     }
        // }

        // // Collect the latest transactions for LIFO, FIFO, and Average
        // foreach ($latestEntriesByDate as $itemId => $entry) {
        //     if (isset($entry['lifo'])) {
        //         $lifo_transaction[] = $entry['lifo'];
        //     }
        //     if (isset($entry['fifo'])) {
        //         $fifo_transaction[] = $entry['fifo'];
        //     }
        //     if (isset($entry['avg'])) {
        //         $avg_transaction[] = $entry['avg'];
        //     }
        // }


        $todaysDate = Carbon::today(); // Aaj ki date lete hain

        $total_sales_order_quantity = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->whereDate('sales_orders.due_date', '<=', $todaysDate) // Due date ko filter karte hain
            ->whereNotIn('so_items.so_dispatch_item_status', ['Pre Closed', 'Closed', 'Cancelled'])
            ->sum('so_items.so_dispatch_rest_qty'); // Sirf total quantity ka sum nikalte hain

        $total_purchase_order_quantity = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->whereDate('purchase_orders.due_date', '<=', $todaysDate) // Due date ko filter karte hain
            ->whereNotIn('po_items.po_dispatch_item_status', ['Pre Closed', 'Closed', 'Cancelled'])
            ->sum('po_items.po_dispatch_rest_qty'); // Sirf total quantity ka sum nikalte hain

        // dd( $total_sales_order_quantity,   $total_purchase_order_quantity);



        $virtual_store = StockItem::sum('weight');
        $outward_data = Outward::where('bill_status', 'bill pending')->count();

        // Get PO and SO totals
        $filteredPOTotals = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->select(
                'po_items.item_category',
                'categories.id as category_id',
                'categories.name as category_name',
                DB::raw("
            SUM(
                CASE 
                    WHEN po_items.po_dispatch_item_status NOT IN ('Pre Closed', 'Closed', 'Cancelled') 
                    THEN po_items.po_dispatch_rest_qty 
                    ELSE 0 
                END
            ) as total_quantity
        ")
            )
            ->groupBy('po_items.item_category', 'categories.name', 'categories.id')
            ->get();

        $filteredSOTotals = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->select(
                'so_items.item_category',
                'categories.name as category_name',
                'categories.id as category_id',
                DB::raw("
            SUM(
                CASE 
                    WHEN so_items.so_dispatch_item_status NOT IN ('Pre Closed', 'Closed', 'Cancelled') 
                    THEN so_items.so_dispatch_rest_qty 
                    ELSE 0 
                END
            ) as total_quantity
        ")
            )
            ->groupBy('so_items.item_category', 'categories.name', 'categories.id')
            ->get();


        $mergedTotals = $filteredPOTotals->map(function ($po) use ($filteredSOTotals) {
            // Find the corresponding SO data by category_id
            $so = $filteredSOTotals->firstWhere('category_id', $po->category_id);

            // Determine PO and SO total quantities
            $poQuantity = $po->total_quantity;
            $soQuantity = $so ? $so->total_quantity : ''; // Use N/A if no SO data found

            // If no PO data exists, mark PO as N/A
            if (!$poQuantity) {
                $poQuantity = '';
            }

            return [
                'category_name' => $po->category_name,
                'category_id' => $po->category_id,
                'po_total_quantity' => $poQuantity,
                'so_total_quantity' => $soQuantity,
            ];
        });

        // Optionally, you could merge missing categories from filteredSOTotals as well
        $filteredSOTotals->each(function ($so) use ($mergedTotals) {
            // If there's no corresponding PO data for this SO category, add a row with N/A for PO quantity
            if (!$mergedTotals->contains('category_id', $so->category_id)) {
                $mergedTotals->push([
                    'category_name' => $so->category_name,
                    'category_id' => $so->category_id,
                    'po_total_quantity' => '', // Set PO as N/A if no PO data exists
                    'so_total_quantity' => $so->total_quantity,
                ]);
            }
        });



        // Process Purchase Orders (PO) Totals
        $filteredPOTotalsPartyWise = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('companies', 'purchase_orders.supplier_id', '=', 'companies.id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->select(
                'companies.company_name as company_name',
                'purchase_orders.supplier_id as party_id',
                DB::raw('GROUP_CONCAT(DISTINCT categories.name SEPARATOR ", ") as category_names'),
                DB::raw("
            SUM(
                CASE 
                    WHEN po_items.po_dispatch_item_status NOT IN ('Pre Closed', 'Closed', 'Cancelled') 
                    THEN po_items.po_dispatch_rest_qty 
                    ELSE 0 
                END
            ) as total_quantity
        ")
            )
            ->groupBy('purchase_orders.supplier_id', 'companies.company_name')
            ->get();

        // Process Sales Orders (SO) Totals
        $filteredSOTotalsPartyWise = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('companies', 'sales_orders.company_id', '=', 'companies.id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->select(
                'companies.company_name',
                'sales_orders.company_id as party_id',
                DB::raw('GROUP_CONCAT(DISTINCT categories.name SEPARATOR ", ") as category_names'),
                DB::raw("
            SUM(
                CASE 
                    WHEN so_items.so_dispatch_item_status NOT IN ('Pre Closed', 'Closed', 'Cancelled') 
                    THEN so_items.so_dispatch_rest_qty 
                    ELSE 0 
                END
            ) as total_quantity
        ")
            )
            ->groupBy('sales_orders.company_id', 'companies.company_name')
            ->get();

        // Merge PO and SO Totals
        $mergedTotalsPartyWise = $filteredPOTotalsPartyWise->map(function ($po) use ($filteredSOTotalsPartyWise) {
            // Find the corresponding SO data by party_id (supplier_id or company_id)
            $so = $filteredSOTotalsPartyWise->first(function ($so) use ($po) {
                return $so->party_id === $po->party_id;
            });

            return [
                'party_id' => $po->party_id,
                'company_name' => $po->company_name,
                'category_names' => $po->category_names,
                'po_total_quantity' => $po->total_quantity ?: '',
                'so_total_quantity' => $so ? $so->total_quantity : '',
            ];
        });

        // Add Missing SO Data if No Matching PO Exists
        $filteredSOTotalsPartyWise->each(function ($so) use ($mergedTotalsPartyWise) {
            if (!$mergedTotalsPartyWise->contains(function ($merged) use ($so) {
                return $merged['party_id'] === $so->party_id;
            })) {
                $mergedTotalsPartyWise->push([
                    'party_id' => $so->party_id,
                    'company_name' => $so->company_name,
                    'category_names' => $so->category_names,
                    'po_total_quantity' => '',
                    'so_total_quantity' => $so->total_quantity,
                ]);
            }
        });



        return view('index', compact(
            'sales_order',
            'purchase_order',
            'my_notes',
            'base_price',
            'virtual_store',
            'company_count',
            'mergedTotals',
            'mergedTotalsPartyWise',
            'total_sales_order_quantity',
            'total_purchase_order_quantity',
            'categories',
            'inventory_transaction',
            'lifo_transaction',
            'fifo_transaction',
            'avg_transaction',
            'lifoData',
            'fifoData',
            'avgData',
        ));
    }




    public function save_notes(Request $request)
    {

        $notes = [
            'description' => $request->description,
        ];
        MyNote::create($notes);
        return redirect()->route('dashboard');
    }

    public function get_category_data(Request $request)
    {
        $category_data = Category::where('id', $request->category_id)->first();
        return response([
            'data' => $category_data
        ]);
    }
    public function base_price_store(Request $request)
    {
        $base_price = [
            'name' => $request->name,
            'price' => $request->price,
            'margin' => $request->margin,
        ];
        Category::where('id', $request->id)->update($base_price);
        return redirect()->route('dashboard');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function getReceivedQtySoItemWise()
    {
        $so_items = DB::table('so_items')
            ->join('categories', 'categories.id', '=', 'so_items.item_category') // Join with categories table
            ->select('categories.name',  DB::raw('SUM(so_items.so_dispatch_rest_qty) as total_qty')) // Select category name and sum of quantity
            ->groupBy('categories.name') // Group by category name
            ->where('so_items.so_dispatch_rest_qty', '!=', 0)
             ->orderBy('categories.name', 'asc')
            ->whereNotIn('so_items.so_dispatch_item_status', ['Pre Closed', 'Cancelled', 'Close'])
            ->get();

        return response()->json([
            'data' => $so_items
        ]);
    }

    public function getReceivedQtyPoItemWise()
    {
        $po_items = DB::table('po_items')
            ->join('categories', 'categories.id', '=', 'po_items.item_category') // Join with categories table
            ->select('categories.name', DB::raw('SUM(po_items.po_dispatch_rest_qty) as total_qty')) // Select category name and sum of quantity
            ->groupBy('categories.name') // Group by category name
            ->where('po_items.po_dispatch_rest_qty', '!=', 0)
             ->orderBy('categories.name', 'asc')
            ->whereNotIn('po_items.po_dispatch_item_status', ['Pre Closed', 'Cancelled', 'Close'])
            ->get();


        return response()->json([
            'data' => $po_items
        ]);
    }


    public function get_so_item(Request $request)
    {
        $todaysDate = Carbon::today();

        $sales_order_due_date = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('companies', 'companies.id', '=', 'sales_orders.company_id')
            ->join('users', 'sales_orders.so_user_id', '=', 'users.id')
            ->select(
                'companies.company_name',
                'users.name',
                'sales_orders.id as so_id',
                'sales_orders.so_number',
                'sales_orders.due_date',
                DB::raw('SUM(so_items.so_dispatch_rest_qty) as total_quantity')
            )
            ->whereDate('sales_orders.due_date', '<=', $todaysDate) // Due date ko filter karte hain
            ->groupBy('sales_orders.id', 'sales_orders.so_number', 'sales_orders.due_date', 'users.name', 'companies.company_name')
            ->get();

        return response()->json([
            'data' => $sales_order_due_date
        ]);
    }


    public function get_po_item(Request $request)
    {
        $todaysDate = Carbon::today();

        $purchase_order_due_date = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('companies', 'companies.id', '=', 'purchase_orders.supplier_id')
            ->join('users', 'purchase_orders.po_user_id', '=', 'users.id')
            ->select(
                'companies.company_name',
                'users.name',
                'purchase_orders.id as po_id',
                'purchase_orders.document_number as po_number',
                'purchase_orders.due_date',
                DB::raw('SUM(po_items.po_dispatch_rest_qty) as total_quantity')
            )
            ->whereDate('purchase_orders.due_date', '<=', $todaysDate) // Due date ko filter karte hain
            ->groupBy('purchase_orders.id', 'purchase_orders.document_number', 'purchase_orders.due_date', 'users.name', 'companies.company_name')
            ->get();

        return response()->json([
            'data' => $purchase_order_due_date
        ]);
    }
}
