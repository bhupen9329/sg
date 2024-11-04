<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoItem extends Model
{
    use HasFactory;

    protected $table = 'po_items';
    protected $fillable = [
        'po_id',
        'item_category',
    
        'po_item_no',
        'item_subcategory',
        'qty',
        'unit_price',
        'po_rest_qty',
        'price',
         'po_item_status',
         'po_dispatch_item_status',
         'po_dispatch_rest_qty',
        
    ];
      
    public $timestamps = true;
}
