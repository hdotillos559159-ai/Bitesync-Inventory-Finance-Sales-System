<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'instructions',
    ];

    /**
     * Recipe product.
     *
     * Each recipe belongs to one product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Recipe ingredients.
     *
     * A recipe can contain many inventory items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }
}