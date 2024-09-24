<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstSetting extends Model
{
    use HasFactory;
    protected $table = 'gst_settings';
    protected $fillable = [
        'gst_prefix',
        'percent' ,
    ];
}
