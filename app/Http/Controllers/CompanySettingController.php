<?php

namespace App\Http\Controllers;

use App\Models\CityState;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\GstSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class CompanySettingController extends Controller
{


    function __construct()
    {
         $this->middleware('permission:GST-index', ['only' => ['gstindex']]);
         $this->middleware('permission:GST-create', ['only' => ['create','gst_store']]);
         $this->middleware('permission:GST-edit', ['only' => ['gst_update']]);
         $this->middleware('permission:GST-delete', ['only' => ['gst_setting_delete','']]);
         $this->middleware('permission:Setting-company', ['only' => ['index','update']]);

    }



    public function index()
    {
        $data = CompanySetting::first(); // Retrieve the first contact
        $states = CityState::select('state')
        ->distinct()->get();

        return view('setting.company.index', compact('data','states'));
    }

    public function update(Request $request)
    {
        // Check if a record with the provided email exists
        $contact = CompanySetting::firstOrNew();
        // Update the existing instance or set the values for the new instance
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone_number = $request->phone_number;
        $contact->country = $request->country;
        $contact->state = $request->state;
        $contact->city = $request->city;
        $contact->address = $request->address;
        $contact->gst_no = $request->gst_no;
        $contact->pan = $request->pan;
        $contact->tan = $request->tan;
        $contact->ac_number = $request->ac_number;
        $contact->ifsc_code = $request->ifsc_code;
        $contact->bank_name = $request->bank_name;
        $contact->branch = $request->branch;
        $contact->custom_due_date = $request->custom_due_date;
        $contact->term_condition = $request->term_condition;
        $contact->updated_at = now(); // Update updated_at

        // Save the instance to the database
        $contact->save();

        // Redirect back to the index page with a success message
        return redirect()->back()->with('update', 'Company Updated Successfully.');
    }
    public function shortage_index()
    {
        $shortage = CompanySetting::first(); // Retrieve the first contact

        return view('setting.shortage.index', compact('shortage' ));
    }
    public function shortage_update(Request $request)
    {
        // dd($request);
        // Validate the request data before inserting
        $request->validate([
            'shortage' => 'required',
        ]);

        // Check if a record with the provided email exists
        $shortage = CompanySetting::firstOrNew();
        // Update the existing instance or set the values for the new instance
        $shortage->shortage = $request->name;

        // Save the instance to the database
        $shortage->save();

        // Redirect back to the index page with a success message
        return redirect()->back()->with('update', 'Shortage Updated Successfully.');
    }

    public function gstindex()
    {
        $data = GstSetting::all(); // Retrieve the first contact
        return view('setting.gst.index', compact('data'));
    }

    public function gst_update(Request $request)
    {
        $check = GstSetting::where('gst_prefix', $request->gst_prefix)->where('id', $request->gst_id)->first();
        if(empty($check)){
            $checkdescription = GstSetting::where('gst_prefix',$request->gst_prefix)->first();
            if(!empty($checkdescription)){
                return redirect()->back()->with('msg', 'GST (' . $request->gst_prefix . ') already exist.');
            }
        }


        $data = [
            'gst_prefix' => $request->gst_prefix,
            'percent' => $request->gst_percent,
        ];
        GstSetting::where('id', $request->gst_id)->update( $data);
        // Redirect back to the index page with a success message
        return redirect()->back()->with('update', 'Gst Updated Successfully.');
    }


    public function gst_store(Request $request)
    {

        $all_item = GstSetting::all();
        $check = [];
        foreach ($all_item as $item) {
            if ($item->gst_prefix == $request->gst_prefix) {
                $check[] = $item->gst_prefix;
            }
        }
        if (!empty($check)) {
            return redirect()->back()->with('msg', 'GST (' . $request->gst_prefix . ') already exist.');
        }

        $gst = New GstSetting();
        $gst->gst_prefix = $request->gst_prefix;
        $gst->percent = $request->gst_percent;
        $gst->created_at = now(); // Update updated_at

        // Save the instance to the database
        $gst->save();

        // Redirect back to the index page with a success message
        return redirect()->back()->with('success', 'GST Added Successfully.');
    }



    public function gst_setting_delete($id)
    {
        GstSetting::where('id',$id)->delete();
        return redirect()->back()->with('delete', 'GST Deleted Successfully.');
    }

    public function get_gst_details(Request $request)
    {
       $gst_details = GstSetting::where('id', $request->item_id)->first();

       return response([
          'data' => $gst_details,
       ]);
    }


}

