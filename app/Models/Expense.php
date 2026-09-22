<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass assigned.
     */
    protected $fillable = [
        'user_id',
        'category',
        'amount',
        'expense_date',
        'description',
        'reference_no',
        'status',
    ];

    /**
     * Automatically convert database values
     * into the appropriate PHP types.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /**
     * The user who recorded this expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}