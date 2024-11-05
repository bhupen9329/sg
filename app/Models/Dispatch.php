<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;
    protected $table = 'dispatches';
    protected $fillable = [
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
    ];
    public $timestamps = true; // Add this line
}
