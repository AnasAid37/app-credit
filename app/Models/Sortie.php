<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\OwnedByUser;

class Sortie extends Model
{
    protected $fillable = [
        'product_id',
        'quantite',
        'nom_client',
        'motif_sortie',
        'total_price',
        'payment_mode',
        'credit_id',
        'user_id',
        'created_by',
    ];

    /**
     * ✅ تطبيق Global Scope
     */
    protected static function booted()
    {
        static::addGlobalScope(new OwnedByUser);
        
        static::creating(function ($sortie) {
            if (auth()->check() && !$sortie->user_id) {
                $sortie->user_id = auth()->id();
            }
            if (auth()->check() && !$sortie->created_by) {
                $sortie->created_by = auth()->id();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isCredit(): bool
    {
        return $this->payment_mode === 'credit';
    }

    public function isCash(): bool
    {
        return $this->payment_mode === 'cash';
    }
}