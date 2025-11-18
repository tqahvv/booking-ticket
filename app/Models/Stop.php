<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'location_id',
        'sequence_no',
        'is_pickup',
        'is_dropoff',
        'address_override',
        'latitude',
        'longitude',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
