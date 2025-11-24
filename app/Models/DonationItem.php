<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'category',
        'condition',
        'quantity',
        'notes'
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
