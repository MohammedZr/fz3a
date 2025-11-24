<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PickupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'city',
        'address_line',
        'contact_phone',
        'preferred_datetime',
        'status'
    ];

    protected $casts = [
        'preferred_datetime' => 'datetime',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
