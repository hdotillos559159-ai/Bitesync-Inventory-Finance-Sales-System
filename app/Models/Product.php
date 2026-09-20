<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'selling_price',
        'description',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Product category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Product recipe.
     *
     * A product can have one recipe.
     */
    public function recipe(): HasOne
    {
        return $this->hasOne(Recipe::class);
    }

    /**
     * Sale items.
     *
     * A product can appear in many sale items.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}