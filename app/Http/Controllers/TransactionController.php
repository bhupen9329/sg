<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
       $data = Transaction::join('categories', 'categories.id', '=', 'stock_transactions.category_id')
       ->join('subcategories', 'stock_transactions.subcategory_id', '=', 'subcategories.id')
       ->join('warehouse', 'stock_transactions.warehouse_id', '=', 'warehouse.id')
       ->join('users', 'stock_transactions.user_id', '=', 'users.id')
       ->select('*', 'categories.name as category_name')
       ->orderBy('categories.id', 'desc')
       ->get();
       return view('stock_transaction.index')->with('data', $data);
    }
}
