<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTypeAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type_id',
        'amenity_id',
        'extra_cost',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }
}
