<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'amount',
        'paid_amount',
        'remaining_amount',
        'reason',
        'status',
        'created_by'
    ];

    protected $attributes = [
        'paid_amount' => 0,
        'remaining_amount' => 0,
        'status' => 'active'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
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