<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $table = 'quotations';

    protected $fillable = [
        'company_id',
        'document_number',
        'document_file',
        'status',
        'total_weight',
        'total_pcs',
        'quotation_date',
        'quotation_type',
        'payment_term',
        'item_category',
        'item_sub_category',
        'gst_type',
        'uom',
        'quantity',
        'length',
        'piece',
        'weight',
        'unit_price',
        'sub_total',
        'loading_cutting',
        'loading_point',
        'charges',
        'freight_charges',
        'additional_charges',
        'sgst',
        'cgst',
        'igst',
        'total_amount',
        'term_and_condition',
    ];
    public $timestamps = true;
}
