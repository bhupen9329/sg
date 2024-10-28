<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FifoTransactionStack extends Model
{
    use HasFactory;

     // Define the table name (optional if it follows the Laravel naming convention)
     protected $table = 'fifo_transaction_stacks';

     // Define fillable properties for mass assignment
     protected $fillable = [
         'fifo_transaction_id',
         'inventory_transaction_id',
         'purchase_date',
         'fifo_transaction_stacks_bal_qty',
         'fifo_transaction_stacks_bal_unit_price',
         'fifo_transaction_stacks_bal_value',
     ];
 
     // Define any relationships if necessary, for example:
     public function fifoTransaction()
     {
         return $this->belongsTo(FifoTransaction::class, 'fifo_transaction_id');
     }
 
     public function inventoryTransaction()
     {
         return $this->belongsTo(InventoryTransaction::class, 'inventory_transaction_id');
     }
}
