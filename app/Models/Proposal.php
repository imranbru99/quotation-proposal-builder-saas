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

class Proposal extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'total_amount' => 'decimal:2',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'signed_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($proposal) {
            if (empty($proposal->view_token)) {
                $proposal->view_token = (string) Str::uuid();
            }

            if (empty($proposal->sign_token)) {
                $proposal->sign_token = (string) Str::uuid();
            }

            if (empty($proposal->proposal_number)) {
                $prefix = 'PROP-' . date('Y') . '-';
                $next = (static::withoutGlobalScopes()->where('proposal_number', 'like', "{$prefix}%")->count()) + 1;
                $proposal->proposal_number = $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
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

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function convertedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'converted_invoice_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ProposalSection::class)->orderBy('sort_order');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProposalMilestone::class)->orderBy('sort_order');
    }

    public function signatures(): MorphMany
    {
        return $this->morphMany(DocumentSignature::class, 'signable');
    }

    public function isSigned(): bool
    {
        return $this->status === 'signed' || !empty($this->signed_at);
    }
}
