<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
    'company_name',
    'address',
    'email',
    'mobile',
    'custom_due_date',
    'type',
    'type',
    'virtual_store',
    ];
    public $timestamps = true; // Add this line
}
