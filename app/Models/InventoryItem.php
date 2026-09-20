<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'unit_id',
        'name',
        'sku',
        'quantity',
        'minimum_stock',
        'maximum_stock',
        'unit_cost',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }
}