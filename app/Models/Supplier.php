<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'status',
        'notes',
    ];

    /**
     * Get all purchases made from this supplier.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(
            Purchase::class
        );
    }

    /**
     * Check whether the supplier is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Check whether the supplier is inactive.
     */
    public function isInactive(): bool
    {
        return $this->status === 'Inactive';
    }
}