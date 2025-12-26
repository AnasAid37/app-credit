<?php
// app/Models/UserProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'adresse',
        'departement',
        'date_embauche',

        'langue',
        'fuseau_horaire',
        'notif_stock_faible',

        'notif_commandes',
        'notif_rapports',
        'notif_promotions',

        'two_factor_enabled',
        'google2fa_secret',
        'password_changed_at',
    ];


    protected $casts = [
        'date_embauche' => 'date',
        'notif_stock_faible' => 'boolean',
        'notif_commandes' => 'boolean',
        'notif_rapports' => 'boolean',
        'notif_promotions' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'password_changed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
