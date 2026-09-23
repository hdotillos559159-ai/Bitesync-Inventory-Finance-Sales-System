<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'sale_date' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function scopeCash($query)
    {
        return $query->where('payment_method', 'cash');
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('sale_date', [$from, $to]);
    }
}
