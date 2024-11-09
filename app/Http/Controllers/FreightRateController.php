<?php

namespace App\Http\Controllers;

use App\Models\FreightRate;
use Illuminate\Http\Request;

class FreightRateController extends Controller
{
    public function index()
    {
        $freight_rate = FreightRate::all();
                               
        // dd($freight_rate);
        return view('inventory.freightinsurance.index',compact('freight_rate'));
    }

    public function create()
    {
        return view('inventory.freightinsurance.create');
    }

//     public function getSubcategories($categoryId)
// {
//     $subcategories = SubCategory::where('category_id', $categoryId)->get();
//     return response()->json($subcategories);
// }

public function store(Request $request)
{

    // dd($request);

    if(($request->freight_rate == null) && ($request->insurance_rate == null)){
        return redirect()->back()->with('msg', 'Please fill atleast freight rate or insurance rate!');
    }


    FreightRate::create([
        'freight_rate_date' => $request->date,
        'freight_rate' => $request->freight_rate,
        'insurance_rate' => $request->insurance_rate,
        'remarks' => $request->remarks,
    ]);
    return redirect()->route('freight_rate.index')->with('success', 'Freight & Insurance Rates saved successfully!');
}

public function edit($id)
{
    $data = FreightRate::find($id)->first();

   return view('inventory.freightinsurance.edit',compact('data'));
}

public function update(Request $request, $id)
{

    if(($request->freight_rate == null) && ($request->insurance_rate == null)){
        return redirect()->back()->with('msg', 'Please fill atleast freight rate or insurance rate!');
    }
    
    $item = FreightRate::findOrFail($id);
    
    $item->freight_rate_date = $request->date;
    $item->freight_rate = $request->freight_rate;
    $item->insurance_rate = $request->insurance_rate;
    $item->remarks = $request->remarks;
  
     $item->save();
 
     return redirect()->route('freight_rate.index')->with('update', 'Item updated successfully.');

}

public function delete( $id)
{
    // dd($id);
    FreightRate::where('id', $id)->delete();
    return redirect()->route('freight_rate.index')->with('delete', 'Freight Rate Item Deleted Successfully');
}
}
