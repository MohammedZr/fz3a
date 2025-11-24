<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'type',
        'amount',
        'currency',
        'status',
        'donor_name',
        'donor_phone',
        'donor_email',
        'is_anonymous'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'amount'       => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function items()
    {
        return $this->hasMany(DonationItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function pickupRequest()
    {
        return $this->hasOne(PickupRequest::class);
    }
    public function attachments()
{
    return $this->hasMany(DonationAttachment::class);
}

}
