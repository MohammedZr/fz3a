<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationAttachment extends Model
{
    protected $fillable = [
        'donation_id',
        'path',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
