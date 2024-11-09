<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;
    protected $table = 'dispatches';
    protected $fillable = [
        'date',
        'po_company_id',
        'so_company_id',
        'po_id',
        'so_id',
        'po_item_id',
        'so_item_id',
        'category_id',
        'subcategory_id',
        'dispatched_quantity',
        'conv_rate',
        'dispatch_unit_price',
        'dispatch_freight',
        'dispatch_other',
        'dispatch_total',
        'dispatch_so_unit_price',
        'dispatch_so_freight',
        'dispatch_so_other',
        'dispatch_so_total',
        'vehicle_number',
        'receiver_person',
        'vehicle_number',
        'remarks',

    ];
    public $timestamps = true; // Add this line
}
