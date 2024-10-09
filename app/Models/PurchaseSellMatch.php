<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseSellMatch extends Model
{
    use HasFactory;

     // Define the table name if necessary (if Laravel doesn't pluralize it correctly)
     protected $table = 'purchase_sell_match';

     // Specify the fillable attributes to allow mass assignment
     protected $fillable = [
         'po_id', 
         'so_id', 
         'matched_quantity',
         'po_rest_quantity',
         'so_rest_quantity',
        
     ];
 
     // Define relationship with PurchaseItem model
     public function purchaseItem()
     {
         return $this->belongsTo(PurchaseOrder::class, 'po_id');
     }
 
     // Define relationship with SellItem model
     public function sellItem()
     {
         return $this->belongsTo(SalesOrder::class, 'so_id');
     }
}
