<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FifoTransaction extends Model
{
    use HasFactory;

    protected $fillable= [
    'inventory_transaction_id',
    'item_id',
    'stock_bal_qty',
    'stock_bal_value',
    'stock_bal_unit_price',
    'stock_position',
    'cogs_qty',
    'cogs_unit_price',
    'cogs_bal_value',
    'actual_sales_qty',
    'actual_sales_unit_price',
    'actual_sales_value',
    'profit_loss',
    'status',
];

public function stacks()
{
    return $this->hasMany(FifoTransactionStack::class);
}

public function details()
{
    return $this->hasMany(FifoTransactionDetail::class);
}

}
