<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inward extends Model
{
    use HasFactory;

    protected $table = 'inwards';

    protected $fillable = [
        'inward_number',
        'supplier_id',
        'po_document_number',
        'inw_remarks',
        'date',
        'total_weight',
        'status',
    ];

    public $timestamp = true;
}
