<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $prefix = 'PAY-' . date('Y') . '-';
                $next = (static::withoutGlobalScopes()->where('payment_number', 'like', "{$prefix}%")->count()) + 1;
                $payment->payment_number = $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            }

            if (empty($payment->receipt_number)) {
                $payment->receipt_number = 'REC-' . strtoupper(bin2hex(random_bytes(3)));
            }
        });

        static::saved(function ($payment) {
            $payment->invoice?->recalculatePaymentStatus();
        });

        static::deleted(function ($payment) {
            $payment->invoice?->recalculatePaymentStatus();
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }
}
