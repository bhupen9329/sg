<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WareHouseModel extends Model
{
    use HasFactory;
    protected $table = 'warehouse';
    
    protected $fillable = [
       'warehouse_title',
       'loading_point',
       'mobile',
       'address',
       'state',
       'city',
       'country',
       'pincode',
       'gstn',
       'pan',
       'tan',
       'cin_no',
       'registration_no',
       'store_manager_id',
    ];

    public $timestamps = true;
}
