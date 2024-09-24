<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'supplier_id',
        'category',
        'sub_category_id',
        'document_number',
        'date',
        'due_date',
        'no_of_due_date',
        'quantity',
        'rest_quantity',
        'price',
        'mode',
        'broker',
        'remark',
        'status',
        'order_age',
        'close_date',
    ];
    public $timestamps = true;
}
