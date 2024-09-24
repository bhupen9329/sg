<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortageSetting extends Model
{
    use HasFactory;

    protected $table = 'shortage_settings';

    protected $fillable = [
        'name'
    ];

    public $timestamps = true ;
}
