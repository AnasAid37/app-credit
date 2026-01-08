<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'description',
        'couleur',
        'icone',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // ============ العلاقات ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ============ Accessors ============

    /**
     * عدد المنتجات في هذه الفئة
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }

    /**
     * إجمالي قيمة المخزون في هذه الفئة
     */
    public function getTotalStockValueAttribute()
    {
        // ✅ غيّرنا prix_achat إلى price
        return $this->products()->sum(\DB::raw('quantite * price'));
    }

    /**
     * المنتجات بمخزون منخفض
     */
    public function getLowStockProductsAttribute()
    {
        return $this->products()
            ->whereRaw('quantite <= seuil_alerte')
            ->count();
    }

    // ============ Scopes ============

    /**
     * الفئات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * الفئات الخاصة بالمستخدم الحالي
     */
    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $query->where('user_id', $userId);
    }
}