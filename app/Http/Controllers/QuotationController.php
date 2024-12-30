<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CityState;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\EmailSetting;
use App\Models\GstSetting;
use App\Models\QtItem;
use App\Models\Quotation;
use App\Models\SubCategory;
use App\Models\WareHouseModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Routing\Controllers\Middleware;

class QuotationController extends Controller
{


    // function __construct()
    // {
    //     $this->middleware('permission:Quotation-index', ['only' => ['index']]);
    //     $this->middleware('permission:Quotation-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:Quotation-view', ['only' => ['edit']]);
    //     $this->middleware('permission:Quotation-edit', ['only' => ['update']]);
    //     $this->middleware('permission:Quotation-delete', ['only' => ['delete']]);
    //     $this->middleware('permission:Quotation-email', ['only' => ['send_email']]);
    // }

    public function index()
    {
        $quotation_data = Quotation::join('companies', 'quotations.company_id', '=', 'companies.id')
            ->select('companies.*', 'quotations.*', 'quotations.created_at as q_created_at', 'quotations.id as q_id')
            ->orderBy('quotations.id', 'desc')
            ->get();

        $company_email = EmailSetting::first();

        return view('quotation.index', compact('quotation_data', 'company_email'));
    }

    public function create(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();
        $warehouse = WareHouseModel::get();
        $item_category = Category::get();
        $company_setting = CompanySetting::first();
        $gstsetting = GstSetting::all();
        $year = date('Y');
        $max_serial_number = Quotation::max('document_number');
        $last_serial_number = substr($max_serial_number, -4);
        $next_serial_number = str_pad((int) $last_serial_number + 1, 4, '0', STR_PAD_LEFT);
        $qt_id = 'QT'.  $year . $next_serial_number;

        $data = [
            'company' => $company,
            'item_category' => $item_category,
            'company_setting' => $company_setting,
            'warehouse' => $warehouse,
            'document_number' =>  $qt_id,
            'gstsetting' =>  $gstsetting,
        ];
        // dd($data);
        return view('quotation.create')->with($data);
    }


    public function store(Request $request)
    {

        // DD($request);
        $Quotation = new Quotation();
        $Quotation->company_id = $request->company_id;
        $Quotation->payment_term = $request->payment_terms;
        $Quotation->quotation_date = $request->date;
        $Quotation->total_weight = $request->total_weight;
        $Quotation->total_pcs = $request->total_pcs;
        $Quotation->loading_point = $request->loading_point;
        // $Quotation->other_gst = $request->other_gst;
        $Quotation->status = 'pending';
        $Quotation->sub_total = $request->sub_total;
        $Quotation->total_sgst = isset($request->total_sgst) && is_numeric($request->total_sgst) ? $request->total_sgst : 0;
        $Quotation->total_cgst = isset($request->total_cgst) && is_numeric($request->total_cgst) ? $request->total_cgst : 0;
        $Quotation->total_igst = isset($request->total_igst) && is_numeric($request->total_igst) ? $request->total_igst : 0;
        $Quotation->additional_charges = $request->additional_charges;
        $Quotation->loading_cutting = $request->loading_cutting;
        $Quotation->freight_charges = $request->freight;
        $Quotation->grand_total = $request->grand_total;
        $Quotation->term_and_condition = $request->term_condition;
        $Quotation->gst_type = $request->gst_type;
        $Quotation->document_number =  $request->document_number;
        $Quotation->document_file =  'uploads/documents/quotation/' . $request->document_number . '/' . $request->document_number . '.pdf';
        $Quotation->save();
        $id = $Quotation->id;
        if ($id) {
            for ($i = 0; $i < count($request->amount); $i++) {
                $QtItem = new QtItem();
                $QtItem->item_category = $request->item_category[$i];
                $QtItem->item_subcategory = $request->item_subcategory[$i];
                $QtItem->qty = $request->qty[$i];
                $QtItem->length = $request->length[$i];
                $QtItem->uom_type = $request->uom[$i];
                $QtItem->price = $request->price[$i] ?? null;
                $QtItem->pcs = $request->pcs[$i] ?? null;
                $QtItem->weight = $request->weight[$i] ?? null;
                $QtItem->amount = $request->amount[$i] ?? null;
                $QtItem->gst_percent = $request->gst_percent[$i];


                // Check if sgst, cgst, and igst are null and assign default values
                $QtItem->sgst = $request->sgst[$i] ?? 0; // Default to 0 if sgst is null
                $QtItem->cgst = $request->cgst[$i] ?? 0; // Default to 0 if cgst is null
                $QtItem->igst = $request->igst[$i] ?? 0; // Default to 0 if igst is null
                $QtItem->qt_id = $id;
                $QtItem->save();
            }
        }

        return redirect()->route('quotation.pdf', $id)->with('success', 'Quotation  Updated Successfully');
    }


    public function show($id)
    {
        $quotation_data = Quotation::join('companies', 'quotations.supplier_id', '=', 'companies.id')
            ->select('companies.*', 'quotations.*', 'quotations.created_at as po_created_at', 'quotations.id as po_id')->where('quotations.id', $id)->first();
        // dd($po_data);
        return view('quotation.show', compact('quotation_data'));
    }
    public function delete($id)
    {
        Quotation::where('id', $id)->delete();
        QtItem::where('qt_id', $id)->delete();
        return redirect()->route('quotation.index')->with('delete', 'Quotation  Updated Successfully');
    }


    public function edit($id)
    {
        $quotaion = Quotation::where('id', $id)->first();

        $company = Company::where('id', $quotaion->company_id)->first();

        $category = Category::all();
        $gstsetting = GstSetting::all();



        // $qt_items =  QtItem::join('categories', 'categories.id', '=', 'qt_items.item_category')
        //     ->join('subcategories', 'subcategories.category_id', '=', 'categories.id')
        //     ->select('*', 'qt_items.weight as qt_weight', 'qt_items.price as qt_price')
        //     ->where('qt_id', $id)->get();

        $qt_items = QtItem::join('categories', 'categories.id', '=', 'qt_items.item_category')
            ->join('subcategories', 'qt_items.item_subcategory', '=', 'subcategories.id')
            ->select('qt_items.*', 'qt_items.weight as qt_weight', 'qt_items.price as qt_price', 'categories.*', 'subcategories.*')
            ->where('qt_id', $id)
            ->get();

            $sub_category = [];

            foreach ($category as $categorys) {
                $subcategory = SubCategory::where('category_id', $categorys->id)->first();
                if ($subcategory !== null) {
                    $sub_category[] = $subcategory;
                }
            }
            

        // dd($sub_category);



        $count = QtItem::where('qt_id', $id)->count();

        $data = [
            'quotaion' => $quotaion,
            'qt_items' => $qt_items,
            'category' => $category,
            'sub_category' => $sub_category,
            'company' => $company,
            'count'  => $count,
            'gstsetting' =>  $gstsetting
        ];
        return view('quotation.edit')->with($data);
    }


    public function update(Request $request, $id)
    {
        $quotation = Quotation::where('id', $id)->first();

        if ($quotation->status == 'sales generated') {
            return redirect()->back()->with('msg', 'Your Quotation are already sales generated');
        }

        $data = [
            'quotation_date' => $request->date,
            'total_weight' => $request->total_weight,
            'total_pcs' => $request->total_pcs,
            'sub_total' => $request->sub_total,
            'total_sgst' => isset($request->total_sgst) && is_numeric($request->total_sgst) ? $request->total_sgst : 0,
            'total_cgst' => isset($request->total_cgst) && is_numeric($request->total_cgst) ? $request->total_cgst : 0,
            'total_igst' => isset($request->total_igst) && is_numeric($request->total_igst) ? $request->total_igst : 0,
            'additional_charges' => $request->additional_charges,
            'loading_cutting' => $request->loading_cutting,
            'freight_charges' => $request->freight,
            'grand_total' => $request->grand_total,
            'term_and_condition' => $request->term_and_condition,
            'payment_term' => $request->payment_terms,
            'loading_point' => $request->loading_point,
        ];


        Quotation::where('id', $id)->update($data);
        QtItem::where('qt_id', $id)->delete();
        //SO Item Code
        for ($i = 0; $i < count($request->amount); $i++) {
            $QtItem = new QtItem();
            $QtItem->item_category = $request->item_category[$i];
            $QtItem->item_subcategory = $request->item_subcategory[$i];
            $QtItem->qty = $request->qty[$i];
            $QtItem->length = $request->length[$i];
            $QtItem->uom_type = $request->uom[$i];
            $QtItem->price = $request->price[$i] ?? null;
            $QtItem->pcs = $request->pcs[$i] ?? null;
            $QtItem->weight = $request->weight[$i] ?? null;
            $QtItem->amount = $request->amount[$i] ?? null;
            $QtItem->gst_percent = $request->gst_percent[$i];
            $QtItem->sgst = $request->sgst[$i] ?? 0; // Default to 0 if sgst is null
            $QtItem->cgst = $request->cgst[$i] ?? 0; // Default to 0 if cgst is null
            $QtItem->igst = $request->igst[$i] ?? 0; // Default to 0 if igst is null
            $QtItem->qt_id = $id;
            $QtItem->save();
        }


        return redirect()->route('quotation.pdf', $id)->with('success', 'Quotation  Updated Successfully');
    }



    public function quotation_pdf($qt_id)
    {
        // dd($qt_id);
        $id = $qt_id;
        $quotation = Quotation::where('id', $id)->first();


        $company = Company::where('id', $quotation->company_id)->first();
        $company_setting = CompanySetting::first();


        $qt_items = QtItem::join('categories', 'categories.id', '=', 'qt_items.item_category')
            ->join('subcategories', 'subcategories.category_id', '=', 'categories.id')
            ->select('*', 'qt_items.weight as qt_weight')
            ->where('qt_id', $id)
            ->get();


        $data = [
            'quotation' => $quotation,
            'company' => $company,
            'company_setting' => $company_setting,
            'qt_items' => $qt_items,
        ];

        set_time_limit(300);

        $pdf = PDF::loadView('invoice_layouts.quotation_pdf', $data);
        $fileName = $quotation->document_number . '.pdf';
        $directoryPath = public_path('uploads/documents/quotation/' . $quotation->document_number);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $file_save = $directoryPath . '/' . $fileName;
        $pdf->save($file_save);
    }

    public function get_email_details(Request $request)
    {
        $quotation = Quotation::where('id', $request->item_id)->first();
        $company = Company::where('id', $quotation->company_id)->first();

        $data = [
            'quotation' => $quotation,
            'company' => $company,
        ];

        return response()->json($data);
    }


    public function send_email(Request $request)
    {
        // dd($request);
        $email_details = EmailSetting::select('*')
            ->first();
        $cc_email = $request->cc;
        $bcc_email = $request->bcc;
        $to_email = $request->to_email;

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $email_details->host;
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Username = $email_details->username;
        $mail->Password = $email_details->key;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $email_details->port;

        $mail->setFrom($email_details->from_address, $email_details->from_name);
        $mail->addAddress($to_email, 'Company Name');
        // Adding CC recipients
        if (!empty($cc_email)) {
            $cc_emails = explode(',', $cc_email);
            foreach ($cc_emails as $cc) {
                $mail->addCC(trim($cc), 'CC Recipient');
            }
        }

        // Adding BCC recipients
        if (!empty($bcc_email)) {
            $bcc_emails = explode(',', $bcc_email);
            foreach ($bcc_emails as $bcc) {
                $mail->addBCC(trim($bcc), 'BCC Recipient');
            }
        }
        $mail->Subject = $request->subject;
        $mail->isHTML(true);
        $mail->Body = $request->body;
        // Check if the body field is empty or not set
        if (empty($request->body)) {
            // Set a default value for the body (an empty string)
            return redirect()->back()->with('msg', 'Oops! Email body is empty. Email not sent.');
        } else {
            // Set the body from the request
            $mail->Body = $request->body;
            $mail->addAttachment($request->attachment);
        }
        $status = $mail->send();


        // $file = $request->attachment; // Path to the file to attach
        // $mail->addAttachment($file);


        if ($status) {
            return redirect()->back()->with('success', 'Email is sent Successfully.');
        } else {
            return redirect()->back()->with('msg', 'Oops! Email not sent.');
        }
    }
}
