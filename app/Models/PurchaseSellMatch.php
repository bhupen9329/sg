<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseSellMatch extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'purchase_sell_match';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'po_id',
        'so_id',
        'po_item_id',
        'so_item_id',
        'matched_quantity',
        'po_item_qty',
        'po_item_rest_quantity',
        'so_item_qty',
        'so_item_rest_quantity',
    ];

    

  
}
