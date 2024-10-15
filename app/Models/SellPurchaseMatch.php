<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPurchaseMatch extends Model
{
    use HasFactory;

    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'sell_purchase_matches';

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
        'so_item_unit_price',
        'so_item_price',
        'po_item_unit_price',
        'po_item_price',
    ];
}
