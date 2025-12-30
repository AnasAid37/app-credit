<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\OwnedByUser;

class Product extends Model
{
    protected $fillable = [
        'price',
        'taille',
        'marque',
        'quantite',
        'seuil_alerte',
        'user_id'  // ✅ أضف هذا
    ];

    /**
     * ✅ تطبيق Global Scope
     */
    protected static function booted()
    {
        static::addGlobalScope(new OwnedByUser);
        
        static::creating(function ($product) {
            if (auth()->check() && !$product->user_id) {
                $product->user_id = auth()->id();
            }
        });
    }

    public function sorties()
    {
        return $this->hasMany(Sortie::class);
    }

    public function isLowStock()
    {
        return $this->quantite <= $this->seuil_alerte && $this->quantite > 0;
    }

    public function isOutOfStock()
    {
        return $this->quantite == 0;
    }
}