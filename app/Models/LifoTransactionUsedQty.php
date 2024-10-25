<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifoTransactionUsedQty extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows the Laravel naming convention)
    protected $table = 'lifo_transaction_used_qties';

    // Define fillable properties for mass assignment
    protected $fillable = [
        'lifo_transaction_id',
        'inventory_transaction_id',
        'lifo_transaction_used_bal_qty',
        'lifo_transaction_used_bal_unit_price',
        'lifo_transaction_used_bal_value',
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
