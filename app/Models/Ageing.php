<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ageing extends Model
{
    use HasFactory;
    protected $table = 'ageings';

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'length',
        'warehouse_id',
        'qty',
        'balance',
        'age',
    ];

    public $timestamps = true;
}
