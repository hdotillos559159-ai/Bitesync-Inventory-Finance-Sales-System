<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Category Types
    |--------------------------------------------------------------------------
    */

    public const TYPE_INVENTORY = 'inventory';

    public const TYPE_PRODUCT = 'product';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isInventoryCategory(): bool
    {
        return $this->type === self::TYPE_INVENTORY;
    }

    public function isProductCategory(): bool
    {
        return $this->type === self::TYPE_PRODUCT;
    }
}