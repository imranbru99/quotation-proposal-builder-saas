<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentSignature extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'consent_confirmed' => 'boolean',
        'signed_at' => 'datetime',
    ];

    public function signable(): MorphTo
    {
        return $this->morphTo();
    }
}

