<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Quotation extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $guarded = ['id'];

    protected $appends = ['total_amount'];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($quotation) {
            if (empty($quotation->view_token)) {
                $quotation->view_token = (string) Str::uuid();
            }

            if (empty($quotation->quotation_number)) {
                $prefix = 'QT-' . date('Y') . '-';
                $next = (static::withoutGlobalScopes()->where('quotation_number', 'like', "{$prefix}%")->count()) + 1;
                $quotation->quotation_number = $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function convertedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'converted_invoice_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order');
    }

    public function signatures(): MorphMany
    {
        return $this->morphMany(DocumentSignature::class, 'signable');
    }

    public function canBeConverted(): bool
    {
        return empty($this->converted_invoice_id) && in_array($this->status, ['accepted', 'approved', 'sent'], true);
    }

    public function isConverted(): bool
    {
        return !empty($this->converted_invoice_id) || $this->status === 'converted';
    }

    public function calculateTotals(): void
    {
        $subtotal = 0;
        $taxAmount = 0;

        foreach ($this->items as $item) {
            $subtotal += (float) ($item->quantity * $item->unit_price);
            $taxAmount += (float) $item->tax_amount;
        }

        $discountAmount = 0;
        if ($this->discount_type === 'percentage') {
            $discountAmount = ($subtotal * ((float) $this->discount_value / 100));
        } else {
            $discountAmount = min($subtotal, (float) $this->discount_value);
        }

        $this->subtotal = $subtotal;
        $this->discount_amount = $discountAmount;
        $this->tax_amount = $taxAmount;
        $this->grand_total = max(0, ($subtotal - $discountAmount + $taxAmount));
        $this->save();
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) ($this->attributes['grand_total'] ?? 0);
    }
}
