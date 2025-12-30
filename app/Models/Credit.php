<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\OwnedByUser;

class Credit extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'amount',
        'paid_amount',
        'remaining_amount',
        'reason',
        'status',
        'created_by'
    ];

    /**
     * ✅ تطبيق Global Scope
     */
    protected static function booted()
    {
        static::addGlobalScope(new OwnedByUser);
        
        // ✅ إضافة user_id تلقائياً عند الإنشاء
        static::creating(function ($credit) {
            if (auth()->check() && !$credit->user_id) {
                $credit->user_id = auth()->id();
            }
            if (auth()->check() && !$credit->created_by) {
                $credit->created_by = auth()->id();
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sorties()
    {
        return $this->hasMany(Sortie::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}