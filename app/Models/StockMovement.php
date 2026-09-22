<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'user_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'reason',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'quantity_before' => 'decimal:2',
        'quantity_after' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Transaction Type Helpers
    |--------------------------------------------------------------------------
    */

    public function isStockIn(): bool
    {
        return $this->type === 'stock_in';
    }

    public function isStockOut(): bool
    {
        return $this->type === 'stock_out';
    }

    public function isAdjustment(): bool
    {
        return $this->type === 'adjustment';
    }

    /*
    |--------------------------------------------------------------------------
    | Reference Helpers
    |--------------------------------------------------------------------------
    |
    | reference_type may contain:
    |
    | Purchase::class
    | Sale::class
    | initial_stock
    | manual_stock_in
    | manual_stock_out
    | inventory_adjustment
    |
    | Because of this mixed reference structure, we intentionally do not
    | use morphTo() here.
    |
    */

    public function isPurchaseReference(): bool
    {
        return $this->reference_type === Purchase::class;
    }

    public function isSaleReference(): bool
    {
        return $this->reference_type === Sale::class;
    }

    public function isInitialStock(): bool
    {
        return $this->reference_type === 'initial_stock';
    }

    public function isManualStockIn(): bool
    {
        return $this->reference_type === 'manual_stock_in';
    }

    public function isManualStockOut(): bool
    {
        return $this->reference_type === 'manual_stock_out';
    }

    public function isInventoryAdjustment(): bool
    {
        return $this->reference_type === 'inventory_adjustment';
    }
}