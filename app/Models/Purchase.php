<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | PURCHASE STATUS
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'Draft';

    public const STATUS_PENDING_APPROVAL = 'Pending Approval';

    public const STATUS_APPROVED = 'Approved';

    public const STATUS_REJECTED = 'Rejected';

    public const STATUS_ORDERED = 'Ordered';

    public const STATUS_PARTIALLY_RECEIVED = 'Partially Received';

    public const STATUS_RECEIVED = 'Received';

    public const STATUS_CANCELLED = 'Cancelled';


    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'supplier_id',
        'purchase_number',
        'purchase_date',
        'expected_date',
        'status',
        'subtotal',
        'tax',
        'total',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'purchase_date' => 'date',
        'expected_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'approved_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | SUPPLIER
    |--------------------------------------------------------------------------
    */

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            Supplier::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PURCHASE ITEMS
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            PurchaseItem::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATED BY
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVED BY
    |--------------------------------------------------------------------------
    */

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }


    public function isPendingApproval(): bool
    {
        return $this->status === self::STATUS_PENDING_APPROVAL;
    }


    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }


    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }


    public function isOrdered(): bool
    {
        return $this->status === self::STATUS_ORDERED;
    }


    public function isPartiallyReceived(): bool
    {
        return $this->status === self::STATUS_PARTIALLY_RECEIVED;
    }


    public function isReceived(): bool
    {
        return $this->status === self::STATUS_RECEIVED;
    }


    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}