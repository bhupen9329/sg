<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvRate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'selected_date',
        'item_price',
        'item_freight',
        'item_insurance',  
        'remarks',
    ];
}
