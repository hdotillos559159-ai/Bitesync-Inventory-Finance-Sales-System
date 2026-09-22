<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_type',
        'supplier_id',
        'source_name',
        'reference_number',
        'receipt_date',
        'notes',
        'total_amount',
        'created_by',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockReceiptItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    /*
    |--------------------------------------------------------------------------
    | Source Helpers
    |--------------------------------------------------------------------------
    */

    public function isSupplierSource(): bool
    {
        return $this->source_type === 'supplier';
    }

    public function isStoreSource(): bool
    {
        return $this->source_type === 'store';
    }

    public function isOtherSource(): bool
    {
        return $this->source_type === 'other';
    }


    /*
    |--------------------------------------------------------------------------
    | Display Helpers
    |--------------------------------------------------------------------------
    */

    public function getSourceLabelAttribute(): string
    {
        if ($this->isSupplierSource()) {
            return $this->supplier?->name ?? 'Supplier';
        }

        return $this->source_name ?: ucfirst($this->source_type);
    }

    public function getReferenceLabelAttribute(): string
    {
        return $this->reference_number ?: '—';
    }
}