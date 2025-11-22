<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_location_id',
        'destination_location_id',
        'operator_id',
        'distance',
        'description',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function origin()
    {
        return $this->belongsTo(Location::class, 'origin_location_id');
    }

    public function destination()
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }

    public function pickups()
    {
        return $this->hasMany(Stop::class)->where('is_pickup', 1);
    }

    public function dropoffs()
    {
        return $this->hasMany(Stop::class)->where('is_dropoff', 1);
    }
}
