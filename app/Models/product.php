<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'taille',
        'marque',
        'quantite',
        'seuil_alerte'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // العلاقة مع حركات المخزون
    public function sorties()
    {
        return $this->hasMany(Sortie::class);
    }

    // التحقق من المنتج منخفض المخزون
    public function isLowStock()
    {
        return $this->quantite <= $this->seuil_alerte && $this->quantite > 0;
    }

    // التحقق من نفاد المخزون
    public function isOutOfStock()
    {
        return $this->quantite == 0;
    }

    // الحصول على حالة المخزون
    public function getStockStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'danger';
        } elseif ($this->isLowStock()) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    // الحصول على نص حالة المخزون
    public function getStockStatusTextAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'Épuisé';
        } elseif ($this->isLowStock()) {
            return 'Faible';
        } else {
            return 'OK';
        }
    }
}