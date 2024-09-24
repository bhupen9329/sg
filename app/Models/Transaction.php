<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'stock_transactions';
    protected $fillable = [
        'ref_id',
        'category_id',
        'subcategory_id',
        'warehouse_id',
        'length',
        'pcs',
        'type',
        'operation',
        'user_id'
    ];
    public $timestamps = true;
}
