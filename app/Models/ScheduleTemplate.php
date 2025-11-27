<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleTemplate extends Model
{
    protected $fillable = [
        'route_id',
        'operator_id',
        'vehicle_type_id',
        'departure_time',
        'travel_duration_minutes',
        'running_days',
        'base_fare',
        'default_seats',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'running_days' => 'array',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
