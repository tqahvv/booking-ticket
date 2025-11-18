<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'display_order',
        'sample_vehicle_type_id',
        'image_id',
        'sample_price_from',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'sample_vehicle_type_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
