<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoItem extends Model
{
    use HasFactory;
    protected $table = 'so_items';
    protected $fillable = [
        'sale_id',
        'item_category',
        'item_subcategory',
        'qty',
        'amount',
    ];
      
    public $timestamps = true;
}
