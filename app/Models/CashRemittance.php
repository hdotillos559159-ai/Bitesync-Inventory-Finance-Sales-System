<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashRemittance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'remittance_date',
        'expected_amount',
        'actual_amount',
        'variance',
        'reference_no',
        'remarks',
        'status',
    ];

    protected $casts = [
        'remittance_date' => 'date',
        'expected_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}