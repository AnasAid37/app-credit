<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'email',
        'password',
        'telephone',
        'is_admin',
        'is_active',
        'subscription_type',
        'subscription_expires_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];




    // ==================== العلاقات ====================

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class)->latest();
    }

    public function sorties()
    {
        return $this->hasMany(Sortie::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, 'user_id');
    }
    public function canUseApp()
    {
        if (!$this->is_active) return false;

        if ($this->subscription_type === 'lifetime') return true;

        if ($this->subscription_type === 'monthly') {
            return $this->subscription_expires_at && now()->lessThan($this->subscription_expires_at);
        }

        return false;
    }

    // ==================== Accessors ====================

    public function getNameAttribute()
    {
        return $this->nom;
    }

    // ==================== إنشاء الـ Profile تلقائياً ====================

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([
                'departement' => 'Gestion de Stock',
                'langue' => 'Français',
                'fuseau_horaire' => 'Africa/Casablanca',
                'notif_stock_faible' => true,
                'notif_commandes' => true,
                'notif_rapports' => true,
                'date_embauche' => now(),
            ]);
        });
    }
}
