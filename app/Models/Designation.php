<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = ['id'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
