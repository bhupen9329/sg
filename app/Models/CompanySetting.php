<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;
    protected $table = 'company_settings';
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'country',
        'state',
        'city',
        'address',
        'gst_no',
        'pan',
        'tan',
        'ac_number',
        'ifsc_code',
        'branch',
        'term_condition',
        'threshold_value',
        'shortage_value',

    ];
    public $timestamps = true; // Add this line
}
