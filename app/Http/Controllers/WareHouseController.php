<?php

namespace App\Http\Controllers;

use App\Models\CityState;
use App\Models\User;
use App\Models\WareHouseModel;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Warehouse-index', ['only' => ['index']]);
        $this->middleware('permission:Warehouse-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:Warehouse-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Warehouse-delete', ['only' => ['delete']]);

    }

    public function index()
    {
        $data = WareHouseModel::join('users', 'warehouse.store_manager_id', '=', 'users.id')
            ->select('*', 'warehouse.id as id')
            ->orderBy('warehouse.id', 'desc')
            ->get();
        return view('warehouse.index', compact('data'));
    }


    public function create()
    {

        $states = CityState::select('state')
            ->distinct()->get();
        $stock_manager = User::role('SM')->get();

        // dd($states);
        return view('warehouse.create', compact('states', 'stock_manager'));
    }


    public function store(Request $request)
    {


        $all_item = WareHouseModel::all();
        $check = [];
        foreach ($all_item as $item) {
            if ($item->warehouse_title == $request->warehouse_title) {
                $check[] = $item->warehouse_title;
            }
        }
        if (!empty($check)) {
            return redirect()->back()->with('msg', 'Warehouse (' . $request->warehouse_title . ') already exist.');
        }
             $data = [
            'warehouse_title' => $request->warehouse_title,
            'mobile' => $request->mobile,
            'pan' => $request->pan,
            'tan' => $request->tan,
            'gstn' => $request->gstn,
            'registration_no' => $request->registration_no,
            'store_manager_id' => $request->store_manager_id,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'country' => $request->country,
            'cin_no' => $request->cin_no,
            'address' => $request->address,
        ];
        WareHouseModel::create($data);
        return redirect()->route('warehouse.index')->with('success', 'Warehouse Created Successfully');
    }
    public function show($id)
    {
        $warehouse = WareHouseModel::find($id);
        $stock_manager = User::join('warehouse', 'users.id', '=', 'warehouse.store_manager_id')
            ->where('users.id', $warehouse->store_manager_id)
            ->first();
        // dd($stock_manager);
        return view('warehouse.show', compact('warehouse', 'stock_manager'));
    }

    public function edit($id)
    {
        $data = WareHouseModel::find($id);
        $states = CityState::select('state')
            ->distinct()->get();
        $states_name = CityState::where('state', $data->state)->select('state')->first();

        $city_name = CityState::where('city', $data->city)->select('city')->first();
        $storemanager = User::join('warehouse', 'users.id', '=', 'warehouse.store_manager_id')
            ->where('users.id', $data->store_manager_id)
            ->first();

        $stor_manager = User::role('SM')->get();
        return view('warehouse.edit', compact('data', 'states', 'city_name', 'states_name', 'stor_manager', 'storemanager'));
    }

    public function update(Request $request, $id)
    {
        $check = WareHouseModel::where('warehouse_title', $request->warehouse_title)->where('id', $request->gst_id)->first();
        if(empty($check)){
            $checkdescription = WareHouseModel::where('warehouse_title',$request->warehouse_title)->first();
            if(!empty($checkdescription)){
                return redirect()->back()->with('msg', 'Warehouse (' . $request->warehouse_title . ') already exist.');
            }
        }

        $data = [
            'warehouse_title' => $request->warehouse_title,
            'mobile' => $request->mobile,
            'pan' => $request->pan,
            'tan' => $request->tan,
            'gstn' => $request->gstn,
            'registration_no' => $request->registration_no,
            'store_manager_id' => $request->store_manager_id,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'country' => $request->country,
            'cin_no' => $request->cin_no,
            'address' => $request->address,
        ];

        WareHouseModel::where('id', $id)->update($data);
        return redirect()->route('warehouse.index')->with('update', 'Warehouse Updated Successfully');
    }

    public function delete($id)
    {
        WareHouseModel::where('id', $id)->delete();
        return redirect()->route('warehouse.index')->with('delete', 'Warehouse Deleted Successfully');
    }


    public function get_city_list(Request $request)
    {
        $data = CityState::where('state', $request->state_name)->get();
        return json_encode($data);
    }
}
