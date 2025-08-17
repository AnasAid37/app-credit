<?php
// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function getTotalCreditAmountAttribute(): float
    {
        return $this->credits()->sum('amount');
    }

    public function getActiveCreditAmountAttribute(): float
    {
        return $this->credits()->where('status', 'active')->sum('amount');
    }

    public function getFormattedPhoneAttribute(): string
    {
        if (!$this->phone) {
            return '';
        }

        // Format marocain : 0612345678 -> 06 12 34 56 78
        return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $this->phone);
    }
}