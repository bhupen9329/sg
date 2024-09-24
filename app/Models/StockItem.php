<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;
    protected $table = 'stock_items';

    protected $fillable = [
        'supplier_id',
        'category_id',
        'sub_category_id',
        'weight',
    ];

    public $timestamps = true;
}
