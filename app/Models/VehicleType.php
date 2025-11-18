<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity_total',
        'number_of_rows',
        'layout_schema',
        'description',
    ];

    protected $casts = [
        'layout_schema' => 'array',
    ];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'vehicle_type_amenities')
            ->withPivot('extra_cost')
            ->withTimestamps();
    }
}
