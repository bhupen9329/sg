<?php

namespace App\Http\Controllers;

use App\Exports\ExportSubCategory;
use App\Imports\SubCategoryImport;
use App\Models\Category;
use App\Models\Company;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SubCategoryController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:Sub-Category-index', ['only' => ['index']]);
        $this->middleware('permission:Sub-Category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:Sub-Category-view', ['only' => ['edit']]);
        $this->middleware('permission:Sub-Category-edit', ['only' => ['update']]);
        $this->middleware('permission:Sub-Category-delete', ['only' => ['delete']]);

    }

    public function index(Request $request)
    {
        
        $subcategory = SubCategory::join('categories', 'subcategories.category_id', '=', 'categories.id')
        ->select('subcategories.*', 'subcategories.id as subcategory_id', 'categories.name as category_name')
        ->orderBy('subcategories.id', 'desc')
        ->get();
        // dd($subcategory);

        foreach ($subcategory as $company) {
            // Decode the JSON string to an array
            $providers = json_decode($company->provider, true);

            if (empty($providers)) {
                $company->totalCount = 0;
            } else {
                $company->totalCount = count($providers);
            }
        }

        $data = [
            'subcategory' => $subcategory,
        ];
        return view('inventory.subcategory.index')->with($data);
    }




    public function create()
    {

        $company_data = Company::all();
        return view('inventory.subcategory.create', compact('company_data'));
    }

    public function store(Request $request)
    {

        $data = [
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category,
            'difference' => $request->diff,
            'created_at' => now(),
            'update_at' => now(),
        ];

        SubCategory::create($data);

        return redirect()->route('subcategory.index')->with('success', 'Sub Category Created Successfully');
    }

    public function edit($id)
    {

        $subcategory = SubCategory::where('subcategories.id', $id)
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->select('subcategories.*', 'subcategories.id as subcategory_id', 'categories.name as category_name')
            ->first();
        $selected_subcategory = SubCategory::where('subcategories.id', $id)
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->first();
        $subcategory_data = Category::all();

        $company_Data = [];

        $providers = json_decode($subcategory->provider, true);
        if (!empty($providers)) {
            $company_Data = Company::whereIn('id', $providers)->get();
        }

        $allProviders = Company::all(); // Get all available providers

        $data = [
            'selected_subcategory' => $selected_subcategory,
            'subcategory_data' => $subcategory_data,
            'subcategory' => $subcategory,
            'provider' => $company_Data,
            'company_data' => $allProviders,
        ];

 
        return view('inventory.subcategory.edit')->with($data);

    }

    public function delete(Request $request, $id)
    {
        // dd($id);
        SubCategory::where('id', $id)->delete();
        return redirect()->route('subcategory.index')->with('delete', 'Sub Category Deleted Successfully');
    }


    public function update(Request $request, $id)
    {

        // dd($request);

        $data = [
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category,
            'difference' => $request->diff,
        ];

        // dd($data);

        SubCategory::where('id', $id)->update($data);
        return redirect()->route('subcategory.index')->with('update', 'Sub Category Updated Successfully');
    }

    public function get_subcategory_list(Request $request)
    {
        $data = SubCategory::where('category_id', $request->item_id)->get();

        return json_encode($data);
    }

    public function get_subcategory_details(Request $request)
    {
        $data = SubCategory::where('subcategories.id', $request->item_id)
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->select('subcategories.*', 'subcategories.id as subcategory_id', 'categories.price as category_price', 'categories.margin as category_margin')
            ->first();

        return json_encode($data);
    }
    public function get_providers_details(Request $request)
    {

        $data = SubCategory::where('id', $request->id)->first();

        $provider_ids = json_decode($data->provider);

        //  provider details
        $provider_details = [];

        foreach ($provider_ids as $provider_id) {
            $provider_data = Company::where('id', $provider_id)->first();

            // If provider data is found
            if ($provider_data) {
                $provider_details[] = $provider_data;
            }
        }

        // Return the provider details as a JSON response
        return response()->json($provider_details);
    }



    public function get_sub_category_name(Request $request)
    {
        //check name data in database
        $category_id = $request->category_id;
        $name = $request->sub_category_name;
        $name_data = SubCategory::whereRaw('LOWER(sub_category) = ?', [$name])
            ->where('category_id', $category_id)
            ->first();


        return response($name_data);
    }
    public function get_sub_category_name_edit(Request $request)
    {
        $sub_category_id = $request->sub_category_id;
        $category_id = $request->category_id;
        $sub_category_name = strtolower($request->sub_category_name);

        // Check if there's another subcategory with the same name but a different ID
        $name_data = SubCategory::whereRaw('LOWER(sub_category) = ?', [$sub_category_name])
            ->where('id', '!=', $sub_category_id)
            ->first();

        return response($name_data);
    }

    public function import(Request $req) 
    {
        $req->validate([
            'file' => 'required|mimes:xlsx,xls'
        ], [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'Only Excel files are allowed (xlsx, xls).',
        ]);

        $model = new SubCategory();
        Excel::import(new SubCategoryImport,request()->file('file'));
        return redirect()->route('subcategory.index')->with('success', 'Sub Category Imported Successfully');
    }

    public function export() 
    {
        return Excel::download(new ExportSubCategory, 'subcategory.xlsx');
    }
}
