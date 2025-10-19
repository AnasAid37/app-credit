<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_id',
        'amount',
        'payment_date',
        'notes',
        'created_by'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}