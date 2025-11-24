<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'passenger_name',
        'passenger_phone',
        'passenger_email',
        'identification_type',
        'identification_number',
        'seat_number',
        'pickup_stop_id',
        'dropoff_stop_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function pickupStop()
    {
        return $this->belongsTo(Stop::class, 'pickup_stop_id');
    }

    public function dropoffStop()
    {
        return $this->belongsTo(Stop::class, 'dropoff_stop_id');
    }
}
