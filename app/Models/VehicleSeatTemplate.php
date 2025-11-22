<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleSeatTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type_id',
        'seat_code',
        'row',
        'col',
        'seat_type',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
