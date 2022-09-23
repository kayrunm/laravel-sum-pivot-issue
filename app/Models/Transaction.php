<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaction extends Model
{
    use HasFactory;

    public function allocatedTo(): BelongsToMany
    {
        return $this->belongsToMany(
            static::class,
            'transaction_transaction',
            'allocated_from_id',
            'allocated_to_id',
        )->withPivot('amount');
    }

    public function allocatedFrom(): BelongsToMany
    {
        return $this->belongsToMany(
            static::class,
            'transaction_transaction',
            'allocated_to_id',
            'allocated_from_id',
        )->withPivot('amount');
    }
}
