<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardItem extends Model
{
    use HasFactory;
    protected $table = 'outward_items';

    protected $fillable = [
        'outward_id',
        'category_id',
        'sub_category_id',
        'length',
        'weight',
        'uom_type',
        'piece',
        'quantity',
        'so_item_id',
        'exceed_pcs'
    ];
}
