<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoItem extends Model
{
    use HasFactory;
    protected $table = 'so_items';
    protected $fillable = [
        'so_id',
        'so_item_no',
        'item_category',
        'item_subcategory',
        'qty',
        'unit_price',
        'so_rest_qty',
        'price'
    ];
      
    public $timestamps = true;
}
