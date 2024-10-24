<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [  'company_name','transaction_date', 'transaction_type', 'item_name', 'quantity', 'unit_price', 'po_item_id', 'so_item_id', 'item_id'];
    
}
