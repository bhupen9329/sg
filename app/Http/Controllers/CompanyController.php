<?php

namespace App\Http\Controllers;

use App\Models\CityState;
use App\Models\Company;
use App\Models\ContactPerson;
use Illuminate\Http\Request;

class CompanyController extends Controller
{


  function __construct()
  {
    $this->middleware('permission:Buyers & Suppliers-index', ['only' => ['index']]);
    $this->middleware('permission:Buyers & Suppliers-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:Buyers & Suppliers-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:Buyers & Suppliers-delete', ['only' => ['delete']]);


  }

  public function index()
  {
    $companies = Company::orderBy('id','desc')
    ->get();
    return view('buyers_suppliers.index', compact('companies'));
  }

  public function create()
  {

    return view('buyers_suppliers.create', );
  }
  public function store(Request $req)
  {
    $data = [
      'company_name' => $req->company_name,
      'address' => $req->address,
      'email' => $req->email,
      'mobile' => $req->mobile,
      'custom_due_date' => $req->custom_due_date,
      'type' => $req->type,
      'virtual_store' => $req->virtual_store,
    ];
    // dd($data);
    $store_data = Company::create($data);
    $id = $store_data->id;
    // dd($id);
     
    return redirect()->route('buyers.index')->with('success', 'Company Created Successfully');
  }

  public function edit($id)
  {
    // dd($id);
    $companies = Company::where('id', $id)->first();
    $contact = ContactPerson::where('company_id', $companies->id)->get();
    $states = CityState::select('state')
      ->distinct()->get();
    $data = [
      'companies' => $companies,
      'contact' => $contact,

    ];
    return view('buyers_suppliers.edit')->with($data);
  }


  public function update(Request $req, $id)
  {

    $vaildation = $req->validate([
      'company_name' => 'required',
      'address' => 'required',
    ]);

    $data = [
      'company_name' => $req->company_name,
      'address' => $req->address,
      'email' => $req->email,
      'mobile' => $req->mobile,
      'custom_due_date' => $req->custom_due_date,
      'type' => $req->type,
      'virtual_store' => $req->virtual_store,
    ];

    Company::where('id', $id)->update($data);


     
    return redirect()->route('buyers.index')->with('update', 'Company Updated Successfully');

  }



  public function delete($id)
  {
    Company::where('id', $id)->delete();
    ContactPerson::where('company_id', $id)->delete();
    return redirect()->route('buyers.index')->with('delete', 'Company Deleted Successfully');
  }

  public function show($id)
  {
    // dd($id);
    $companies = Company::where('id', $id)->first();
    $contact = ContactPerson::where('company_id', $id)->get();
    $data = [
      'companies' => $companies,
      'contact' => $contact,
    ];
    return view('buyers_suppliers.show')->with($data);
  }

  public function get_check_buyer_supplier_name(Request $request)
  {
    //check name data in database
    $name = $request->buyer_supplier_name;
    $name_data = Company::whereRaw('LOWER(company_name) = ?', [$name])
      ->first();


    return response($name_data);
  }
  public function get_buyer_supplier_name_edit(Request $request)
  {
    //check name data in database

    $name = $request->name;
    $company_id = $request->company_id;
    $name_data = Company::whereRaw('LOWER(company_name) = ?', [$name])
      ->where('id', '!=', $company_id)
      ->first();

    return response($name_data);
  }
}
