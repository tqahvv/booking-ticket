<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'schedule_template_id',
        'vehicle_type_id',
        'operator_id',
        'departure_datetime',
        'arrival_datetime',
        'total_seats',
        'seats_available',
        'base_fare',
        'locked_until',
        'status',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function pricingOptions()
    {
        return $this->hasMany(PricingOption::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
