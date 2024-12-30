<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    // function __construct()
    // {
    //      $this->middleware('permission:Category-index', ['only' => ['index']]);
    //      $this->middleware('permission:Category-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:Category-view', ['only' => ['edit']]);
    //      $this->middleware('permission:Category-edit', ['only' => ['update']]);
    //      $this->middleware('permission:Category-delete', ['only' => ['delete']]);

    // }

    public function index(Request $request)
    {
        $category = Category::orderBy('id', 'desc')->get();
        $category = Category::all();
        
        foreach ($category as $company) {
            // Decode the JSON string to an array
            $providers = json_decode($company->provider, true);

            if (empty($providers)) {
                $company->totalCount = 0;
            } else {
                $company->totalCount = count($providers);
            }
        }

        $data = [
            'category' => $category,
        ];

        return view('inventory.category.index', $data);

        // dd($data);

    }

    public function create()
    {
        $company_data = Company::all();
        return view('inventory.category.create', compact('company_data'));
    }

    public function store(Request $request)
    {
   
        $category_check = Category::where('name', $request->name)->first();
        if($category_check){
          return redirect()->back()->with('msg', 'Base Item already exist');
        }
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'margin' => $request->margin,
            'created_at' => now(),
            'update_at' => now(),
        ];
        // dd($data);
        Category::create($data);

        return redirect()->route('category.index')->with('success', 'Base Item Created Successfully');
    }
    public function edit($id)
    {

        $category = Category::where('id', $id)->first();

        $company_Data = [];
        $providers = json_decode($category->provider, true);
        if (!empty($providers)) {
            $company_Data = Company::whereIn('id', $providers)->get();
        }

        $allProviders = Company::all(); // Get all available providers

        $data = [
            'category' => $category,
            'provider' => $company_Data,
            'company_data' => $allProviders,
        ];

        // dd($data);
        return view('inventory.category.edit')->with($data);

    }
    public function update(Request $request , $id)
    {

        $category_check = Category::where('name', $request->name)->first();
        if($category_check){
          return redirect()->back()->with('msg', 'Base Item already exist');
        }

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'margin' => $request->margin,
        ];
        // dd($data);
        Category::where('id', $id)->update($data);
        return redirect()->route('category.index')->with('update', 'Base Item Updated Successfully');

    }



    public function delete(Request $request, $id)
    {
        Category::where('id', $id)->delete();
        return redirect()->route('category.index')->with('delete', 'Base Item Deleted Successfully');
    }

    public function get_category_name(Request $request)
    {
        //check name data in database
        $name = $request->name ;
        // $name = $request->category_id ;
        $name_data = Category::whereRaw('LOWER(name) = ?', [$name])->first();

         
        return response($name_data);
    }
    public function get_category_name_edit(Request $request)
    {
        //check name data in database
        $name = $request->name ;
        $category_id = $request->category_id ;
        $name_data = Category::whereRaw('LOWER(name) = ?', [$name])
            ->where('id', '!=', $category_id)
            ->first();

       
        return response($name_data);
    }
}
