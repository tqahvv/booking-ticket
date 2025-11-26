<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'bank_name',
        'account_number',
        'account_name',
        'amount',
        'status',
        'expires_at',
        'confirmed_at'
    ];

    public function bookings()
    {
        return $this->belongsTo(Booking::class);
    }
}
