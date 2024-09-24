<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoReceivedQuantity extends Model
{
    use HasFactory;

    protected $table = 'po_received_quantity';

    protected $fillable = [
        'po_id',
        'received_quantity',
    ];

    public $timestamps = true;
}
