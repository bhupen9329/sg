<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardItem extends Model
{
    use HasFactory;

    protected $table = 'inward_items';

    protected $fillable = [
        'inward_id',
        'category_id',
        'sub_category_id',
        'weight',
    ];

    public $timestamp = true;
}