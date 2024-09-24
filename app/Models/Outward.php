<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outward extends Model
{
    use HasFactory;
    protected $table = 'outwards';
    protected $fillable = [
        'outward_number',
        'company_id',
        'supplier_id',
        'so_number',
        'type',
        'status',
        'date',
        'vehicle_number',
        'total_weight',
        'loading_charges',
        'additional_charges',
        'freight',
        'document_number',
        'supervisor',
        'bill_status',
        'remarks',
    ];
}
