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
         'purchase_item_id', 
         'sell_item_id', 
         'matched_quantity'
     ];
 
     // Define relationship with PurchaseItem model
     public function purchaseItem()
     {
         return $this->belongsTo(PurchaseItem::class, 'purchase_item_id');
     }
 
     // Define relationship with SellItem model
     public function sellItem()
     {
         return $this->belongsTo(SellItem::class, 'sell_item_id');
     }
}
