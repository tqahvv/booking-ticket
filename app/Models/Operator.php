<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo_image_id',
        'rating',
        'contact_info',
    ];

    public function logoImage()
    {
        return $this->belongsTo(Image::class, 'logo_image_id');
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
