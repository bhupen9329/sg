<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;
    protected $table = 'contact_details';
    protected $fillable = [
        'company_id',
        'contact_name', 
        'contact_designation', 
        'contact_mobile', 
    ];
    public $timestamps = true; // Add this line
}
