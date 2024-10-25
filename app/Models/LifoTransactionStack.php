<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifoTransactionStack extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows the Laravel naming convention)
    protected $table = 'lifo_transaction_stacks';

    // Define fillable properties for mass assignment
    protected $fillable = [
        'lifo_transaction_id',
        'inventory_transaction_id',
        'purchase_date',
        'lifo_transaction_stacks_bal_qty',
        'lifo_transaction_stacks_bal_unit_price',
        'lifo_transaction_stacks_bal_value',
    ];

    // Define any relationships if necessary, for example:
    public function lifoTransaction()
    {
        return $this->belongsTo(LifoTransaction::class, 'lifo_transaction_id');
    }

    public function inventoryTransaction()
    {
        return $this->belongsTo(InventoryTransaction::class, 'inventory_transaction_id');
    }
}
