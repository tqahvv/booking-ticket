<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'ticket_code',
        'issued_at',
        'valid_from',
        'valid_to',
        'seat_number',
        'status',
        'qr_code_data',
        'e_ticket_url',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
