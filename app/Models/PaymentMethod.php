<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'details',
        'image',
        'active_flag',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getImageUrlAttribute()
    {
        $defaultImage = 'storage/uploads/images/default.jpg';

        if (!$this->image) {
            return asset($defaultImage);
        }

        if (!str_starts_with($this->image, 'storage/')) {
            $path = 'storage/' . ltrim($this->image, '/');
        }

        return asset($path);
    }
}
