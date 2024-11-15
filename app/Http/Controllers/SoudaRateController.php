<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\ConvRate;

class SoudaRateController extends Controller
{
    public function index()
    {
        $convItems = ConvRate::join('subcategories','subcategories.id','=','conv_rates.subcategory_id')
                                ->join('categories','categories.id','=','conv_rates.category_id')
                                ->select('categories.*', 'subcategories.*', 'conv_rates.*', 'conv_rates.id as conv_id')
                                ->orderBy('conv_rates.selected_date', 'desc')->get();
        // dd($convItems);
        return view('inventory.soudarate.index',compact('convItems'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = SubCategory::all();
        // dd($convitems);
        return view('inventory.soudarate.create',compact('categories','subcategories'));
    }

    public function getSubcategories($categoryId)
{
    $subcategories = SubCategory::where('category_id', $categoryId)->get();
    return response()->json($subcategories);
}

public function store(Request $request)
{

    ConvRate::create([
        'category_id' => $request->category_id,
        'subcategory_id' => $request->subcategory_id,
        'selected_date' => $request->selected_date,
        'item_price' => $request->item_price,
        'remarks' => $request->remarks,
    ]);


    return redirect()->route('rate.index')->with('success', 'Item Conv Rates saved successfully!');
}

public function edit($id)
{
    $item = ConvRate::where('id', $id)->first();
    $categories = Category::all();
    $subcategories = SubCategory::all();
   return view('inventory.soudarate.edit',compact('item','categories','subcategories'));
}

public function update(Request $request, $id)
{
    
    $item = ConvRate::findOrFail($id);
    
    
    
    $item->category_id = $request->category_id;
    $item->subcategory_id = $request->subcategory_id;
    $item->selected_date = $request->selected_date;
    $item->item_price = $request->item_price;
    $item->remarks = $request->remarks;
    
    // dd($item);
     $item->save();
 
     return redirect()->route('rate.index')->with('success', 'Item updated successfully.');

}

public function delete( $id)
{
    // dd($id);
    ConvRate::where('id', $id)->delete();
    return redirect()->route('rate.index')->with('delete', 'Conv Item Deleted Successfully');
}

}
