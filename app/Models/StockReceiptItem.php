<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReceiptItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_receipt_id',
        'inventory_item_id',
        'quantity',
        'unit_price',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function stockReceipt(): BelongsTo
    {
        return $this->belongsTo(StockReceipt::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Calculation Helper
    |--------------------------------------------------------------------------
    */

    public function calculateTotal(): float
    {
        return (float) $this->quantity * (float) $this->unit_price;
    }
}