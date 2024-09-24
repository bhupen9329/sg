<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\QtItem;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\SoItem; 

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function quotation_pdf($qt_id)
    {
        $id = $qt_id;
        $quotation = Quotation::where('id', $id)->first();
        $company = Company::where('id', $quotation->company_id)->first();
        $company_setting = CompanySetting::first();
        $qt_items = QtItem::join('categories', 'categories.id', '=', 'qt_items.item_category')
            ->join('subcategories', 'qt_items.item_subcategory', '=', 'subcategories.id')
            ->select('*', 'qt_items.weight as qt_weight', 'qt_items.price as qt_price')
            ->where('qt_id', $id)
            ->get();
            // dd($qt_items);

            // $qt_items = QtItem::join('categories as c', 'qt_items.item_category', '=', 'c.id')
            // ->join('subcategories as sc', 'qt_items.item_subcategory', '=', 'sc.id')
            // ->select(
            //     'c.name as category_name',
            //     'sc.sub_category as subcategory_name',
            //     'qt_items.*'
            // )
            // ->where('qt_items.qt_id', 5)
            // ->get();
            $totalAmountInWords = $this->convertToWords($quotation->grand_total);



        $data = [
            'quotation' => $quotation,
            'company' => $company,
            'company_setting' => $company_setting,
            'qt_items' => $qt_items,
            'totalAmountInWords' => $totalAmountInWords,
        ];
        // dd(  $data);

        $pdf = PDF::loadView('invoice_layouts.quotation_pdf', $data);
        $fileName = $quotation->document_number . '.pdf';
        $directoryPath = public_path('uploads/documents/quotation/' . $quotation->document_number);
        // return $pdf->download('example.pdf');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $file_save = $directoryPath . '/' . $fileName;
        $pdf->save($file_save);
        // dd($file_save);
        return redirect()->route('quotation.index')->with('success', 'Quotation  Create Successfully');
    }

    public function sales_order_pdf($id)
    {
        // Fetching the sales order by id
        $soItem = SalesOrder::leftJoin('quotations', 'sales_orders.qt_id', '=', 'quotations.id')
        ->select('sales_orders.*', 'quotations.document_number') // Specify columns you need, avoid ambiguity
        ->where('sales_orders.id', $id) // Specify table name for 'id'
        ->first();
        
        if (!$soItem) {
            return redirect()->back()->with('error', 'Sales order not found.');
        }
        
        // Fetching the associated company
        $company = Company::where('id', $soItem->company_id)->first();
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found.');
        }
        
        // Fetching the company settings
        $company_setting = CompanySetting::first();
        
        // Fetching the sales order items
        $so_items = SoItem::join('categories', 'categories.id', '=', 'so_items.item_category')
        ->join('subcategories', 'so_items.item_subcategory', '=', 'subcategories.id')
        ->select('*', 'so_items.weight as so_weight', 'so_items.price as so_price')
        ->where('so_items.sale_id', $id)
        ->get();
        // dd($so_items);
         // Example usage
         $totalAmountInWords = $this->convertToWords($soItem->grand_total);
        // Preparing data to pass to the PDF view
        $data = [
            'soItem' => $soItem,
            'company' => $company,
            'company_setting' => $company_setting,
            'so_items' => $so_items,
            'totalAmountInWords' => $totalAmountInWords,
        ];
        
        // dd($soItem->so_type);
        
        // Loading the view and generating the PDF
        $pdf = PDF::loadView('invoice_layouts.sales_order_pdf', $data);
        
        // Constructing the file name and directory path
        $fileName = $soItem->so_number.'.pdf';
        $directoryPath = public_path('uploads/documents/sales/'. $soItem->so_number);
        
        // Creating the directory if it doesn't exist
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }
        
        // Saving the PDF file
        $file_save = $directoryPath . '/' . $fileName;
        $pdf->save($file_save);
        
        // Redirecting back with success message
        return redirect()->route('sales.index')->with('success', 'Sales Order created successfully');
    }

    
    
        function convertToWords($number) {
    // Arrays to store number words
    $words = [
        'Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
        'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    ];
    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    $units = ['', 'Thousand', 'Lakh', 'Crore'];

    // Function to convert numbers to words
    function numberToWords($num, &$words, &$tens, &$units) {
        if ($num == 0) {
            return 'Zero';
        }

        if ($num < 20) {
            return $words[$num];
        } elseif ($num < 100) {
            return $tens[floor($num / 10)] . ($num % 10 > 0 ? ' ' . $words[$num % 10] : '');
        } elseif ($num < 1000) {
            return $words[floor($num / 100)] . ' Hundred' . ($num % 100 > 0 ? ' ' . numberToWords($num % 100, $words, $tens, $units) : '');
        } elseif ($num < 100000) { // For Thousands
            return numberToWords(floor($num / 1000), $words, $tens, $units) . ' Thousand' . ($num % 1000 > 0 ? ' ' . numberToWords($num % 1000, $words, $tens, $units) : '');
        } elseif ($num < 10000000) { // For Lakhs
            return numberToWords(floor($num / 100000), $words, $tens, $units) . ' Lakh' . ($num % 100000 > 0 ? ' ' . numberToWords($num % 100000, $words, $tens, $units) : '');
        } else { // For Crores
            return numberToWords(floor($num / 10000000), $words, $tens, $units) . ' Crore' . ($num % 10000000 > 0 ? ' ' . numberToWords($num % 10000000, $words, $tens, $units) : '');
        }
    }

    // Ensure number is a valid number
    if (!is_numeric($number)) {
        return 'Invalid number';
    }

    // Split the number into whole and decimal parts
    $wholePart = floor($number);
    $decimalPart = round(($number - $wholePart) * 100);

    $wholeWords = numberToWords($wholePart, $words, $tens, $units);
    $decimalWords = numberToWords($decimalPart, $words, $tens, $units);

    if ($decimalPart == 0) {
        return $wholeWords . ' Rupees Only';
    } else {
        return $wholeWords . ' Rupees and ' . $decimalWords . ' Paisa Only';
    }
}
   
}    