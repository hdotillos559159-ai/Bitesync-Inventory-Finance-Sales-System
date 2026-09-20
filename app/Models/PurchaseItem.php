<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'purchase_id',
        'inventory_item_id',
        'quantity',
        'unit_cost',
        'subtotal',
        'received_quantity',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'received_quantity' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | PURCHASE
    |--------------------------------------------------------------------------
    */

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(
            Purchase::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY ITEM
    |--------------------------------------------------------------------------
    */

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(
            InventoryItem::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REMAINING QUANTITY
    |--------------------------------------------------------------------------
    */

    public function getRemainingQuantityAttribute(): float
    {
        return max(
            0,
            (float) $this->quantity -
            (float) $this->received_quantity
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FULLY RECEIVED
    |--------------------------------------------------------------------------
    */

    public function isFullyReceived(): bool
    {
        return (float) $this->received_quantity >=
            (float) $this->quantity;
    }
}