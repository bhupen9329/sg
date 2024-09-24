<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;
    protected  $table = 'stock_adjustments';
    protected $fillable = [
        'adjustment_number',
        'user_id',
        'warehouse_id',
        'date',
        'remark',
    ];
    public $timestamps = true;
}
