<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AverageTransactionStack extends Model
{
    use HasFactory;

     // Define the table name (optional if it follows the Laravel naming convention)
     protected $table = 'average_transaction_stacks';

     // Define fillable properties for mass assignment
     protected $fillable = [
         'average_transaction_id',
         'inventory_transaction_id',
         'purchase_date',
         'average_transaction_stacks_bal_qty',
         'average_transaction_stacks_bal_unit_price',
         'average_transaction_stacks_bal_value',
     ];
 
     // Define any relationships if necessary, for example:
     public function averageTransaction()
     {
         return $this->belongsTo(AverageTransaction::class, 'average_transaction_id');
     }
 
     public function inventoryTransaction()
     {
         return $this->belongsTo(InventoryTransaction::class, 'inventory_transaction_id');
     }

}
