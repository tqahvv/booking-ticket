<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'fare_class',
        'price',
        'currency',
        'valid_from',
        'valid_to',
        'min_passenger',
        'max_passenger',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
