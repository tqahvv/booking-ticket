<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'linked_type',
        'linked_id',
        'url',
        'alt_text',
        'sort_order',
    ];

    public function getUrlAttribute($value)
    {
        if (!empty($value)) {
            if (!str_starts_with($value, 'storage/')) {
                $value = 'storage/' . ltrim($value, '/');
            }
            return asset($value);
        }
        return asset('storage/uploads/images/default.jpg');
    }
}
