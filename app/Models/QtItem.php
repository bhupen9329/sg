<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QtItem extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'qt_items';
    protected $fillable = [
    'item_category', 
    'item_subcategory', 
    'qty', 
    'length', 
    'uom_type',
    'pcs', 
    'weight', 
    'price', 
    'gst_percent',
    'sgst',
    'cgst', 
    'igst', 
];
public $timestamps = true; 
}
