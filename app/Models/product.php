<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\OwnedByUser;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'price',
        'taille',
        'marque',
        'quantite',
        'seuil_alerte',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantite' => 'integer',
        'seuil_alerte' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * تطبيق Global Scope
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

    // ============ العلاقات ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sorties()
    {
        return $this->hasMany(Sortie::class);
    }

    // ✅ إذا كان لديك جدول entrees (المدخلات/المشتريات)
    public function entrees()
    {
        return $this->hasMany(Entree::class);
    }

    // ============ Accessors ============

    public function getStockStatusTextAttribute()
    {
        if ($this->quantite == 0) {
            return 'Rupture de stock';
        } elseif ($this->quantite <= $this->seuil_alerte) {
            return 'Stock faible';
        }
        return 'En stock';
    }

    public function getStockStatusAttribute()
    {
        if ($this->quantite == 0) {
            return 'danger';
        } elseif ($this->quantite <= $this->seuil_alerte) {
            return 'warning';
        }
        return 'success';
    }

    // ============ دوال مساعدة ============

    public function isLowStock()
    {
        return $this->quantite <= $this->seuil_alerte && $this->quantite > 0;
    }

    public function isOutOfStock()
    {
        return $this->quantite == 0;
    }

    // ============ Scopes ============

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantite <= seuil_alerte');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantite', 0);
    }
}