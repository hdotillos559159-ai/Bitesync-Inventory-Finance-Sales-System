<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'sale_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'payment_method',
        'amount_received',
        'change',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_received' => 'decimal:2',
            'change' => 'decimal:2',
        ];
    }

    /**
     * Sale items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * User who created the sale.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Determine whether the sale is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    /**
     * Determine whether the sale is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }
}