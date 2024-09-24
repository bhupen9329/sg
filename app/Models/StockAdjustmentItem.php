<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    use HasFactory;
    protected $table = 'stock_adjustment_items';

    protected $fillable = [
        'adjustment_id',
        'warehouse_id',
        'category_id',
        'sub_category_id',
        'length',
        'weight',
        'type',
        'piece',
        'quantity',
    ];

    public $timestamps = true;
}
