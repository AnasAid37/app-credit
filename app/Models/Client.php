<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\OwnedByUser;

class Client extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'user_id'  // ✅ أضف هذا
    ];

    /**
     * ✅ تطبيق Global Scope
     */
    protected static function booted()
    {
        static::addGlobalScope(new OwnedByUser);
        
        static::creating(function ($client) {
            if (auth()->check() && !$client->user_id) {
                $client->user_id = auth()->id();
            }
        });
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function getTotalCreditAmountAttribute(): float
    {
        return $this->credits()->sum('amount');
    }
}