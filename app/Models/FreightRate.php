<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreightRate extends Model
{
    use HasFactory;
    protected $table = 'freight_rate';
    protected $fillable = [
        'freight_rate_date',
        'freight_rate' ,
        'insurance_rate',
        'remarks' ,
    ];

}
