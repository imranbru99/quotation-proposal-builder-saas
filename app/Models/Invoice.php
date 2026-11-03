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

class Invoice extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $guarded = ['id'];

    protected $appends = ['total_amount'];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($invoice) {
            if (empty($invoice->view_token)) {
                $invoice->view_token = (string) Str::uuid();
            }

            if (empty($invoice->invoice_number)) {
                $prefix = 'INV-' . date('Y') . '-';
                $next = (static::withoutGlobalScopes()->where('invoice_number', 'like', "{$prefix}%")->count()) + 1;
                $invoice->invoice_number = $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            }

            if ($invoice->balance_due === null || $invoice->balance_due == 0) {
                $invoice->balance_due = $invoice->grand_total;
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

    public function sourceQuotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'source_quotation_id');
    }

    public function sourceProposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'source_proposal_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function signatures(): MorphMany
    {
        return $this->morphMany(DocumentSignature::class, 'signable');
    }

    public function recalculatePaymentStatus(): void
    {
        $paid = (float) $this->payments()->sum('amount');
        $grandTotal = (float) $this->grand_total;
        $balance = max(0, $grandTotal - $paid);

        $this->amount_paid = $paid;
        $this->balance_due = $balance;

        if ($balance <= 0 && $grandTotal > 0) {
            $this->status = 'paid';
            $this->paid_at = now();
        } elseif ($paid > 0 && $balance > 0) {
            $this->status = 'partially_paid';
        } elseif ($this->due_date && $this->due_date->isPast() && $this->status !== 'paid') {
            $this->status = 'overdue';
        }

        $this->save();
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) ($this->attributes['grand_total'] ?? 0);
    }
}
