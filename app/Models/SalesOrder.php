<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;
    protected $table = 'sales_orders';
    protected $fillable = [
        'company_id',
        'virtual_store_id',
        'so_number',
        'address',
        'date',
        'due_date',
        'status',
        'remarks',
        'terms_condition',
        'total_quantity',
        'total_amount',
        'match_position',
        'rest_quantity',
        // 'remarks',
    ];
    
    public $timestamps = true;
}
