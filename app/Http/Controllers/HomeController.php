<?php

namespace App\Http\Controllers;

use App\Models\BasePrice;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Inward;
use App\Models\MyNote;
use App\Models\Outward;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\SoItem;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $sales_order = SalesOrder::whereIn('status', ['pending', 'partial pending'])->count();
        $purchase_order = PurchaseOrder::whereIn('status', ['Open', 'Partial Received'])->count();
        $company_count = Company::all()->count();
        $my_notes = MyNote::latest()->first();
        $base_price = Category::get();
        $CompanySetting_data = CompanySetting::first();
        $threshold_value = (int)$CompanySetting_data->threshold_value;


        $virtual_store = StockItem::sum('weight');
        $outward_data = Outward::where('bill_status', 'bill pending')->count();

        // Get Purchase Order Totals
        $filteredPOTotals = PurchaseOrder::join('po_items', 'purchase_orders.id', '=', 'po_items.po_id')
            ->join('categories', 'po_items.item_category', '=', 'categories.id')
            ->select(
                'po_items.item_category',
                'categories.id as category_id',
                'categories.name as category_name',
                DB::raw('SUM(po_items.po_dispatch_rest_qty) as total_quantity')
            )
            ->groupBy('po_items.item_category', 'categories.name', 'categories.id')
            ->get();

        // Get Sales Order Totals
        $filteredSOTotals = SalesOrder::join('so_items', 'sales_orders.id', '=', 'so_items.so_id')
            ->join('categories', 'so_items.item_category', '=', 'categories.id')
            ->select(
                'so_items.item_category',
                'categories.name as category_name',
                'categories.id as category_id',
                DB::raw('SUM(so_items.so_dispatch_rest_qty) as total_quantity')
            )
            ->groupBy('so_items.item_category', 'categories.name', 'categories.id')
            ->get();

        // Merge the two collections by category_id
        $mergedTotals = $filteredPOTotals->map(function ($po) use ($filteredSOTotals) {
            $so = $filteredSOTotals->firstWhere('category_id', $po->category_id);
            return [
                'category_name' => $po->category_name,
                'category_id' => $po->category_id,
                'po_total_quantity' => $po->total_quantity,
                'so_total_quantity' => $so ? $so->total_quantity : 0,
            ];
        });


        // dd($virtual_store);
        return view('index', compact('sales_order', 'purchase_order', 'my_notes', 'base_price', 'virtual_store', 'company_count', 'mergedTotals',));
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
}
