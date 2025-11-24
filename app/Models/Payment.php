<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'payment_method_id',
        'amount',
        'currency',
        'status',
        'provider',
        'provider_payment_id',
        'provider_payer_id',
        'provider_payload'
    ];

    protected $casts = [
        'amount' => 'float',
        'provider_payload' => 'array'
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
