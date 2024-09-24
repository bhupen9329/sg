<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasePrice extends Model
{
    use HasFactory;
    protected $table = 'base_prices';

    protected $fillable = [
        'category',
        'price',
        'margin',
    ];

    public $timestamps = true;
}
