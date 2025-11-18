<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image_url',
        'user_id',
        'status',
        'reading_time',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getProcessedContentAttribute()
    {
        $content = $this->attributes['content'];

        $oldPrefix = 'uploads/images/';

        $storageRoot = rtrim(asset('storage'), '/');

        $newPrefix = $storageRoot . '/' . $oldPrefix;

        $searchString = 'src="' . $oldPrefix;
        $replaceString = 'src="' . $newPrefix;

        if (Str::contains($content, $searchString)) {
            $content = str_replace($searchString, $replaceString, $content);
        }

        $searchStringSingle = "src='" . $oldPrefix;
        $replaceStringSingle = "src='" . $newPrefix;

        if (Str::contains($content, $searchStringSingle)) {
            $content = str_replace($searchStringSingle, $replaceStringSingle, $content);
        }

        return $content;
    }

    public function getImageUrlAttribute($value)
    {
        $defaultImage = 'storage/uploads/images/default.jpg';

        if (empty($value)) {
            return asset($defaultImage);
        }

        if (!str_starts_with($value, 'storage/')) {
            $value = 'storage/' . ltrim($value, '/');
        }

        return asset($value);
    }
}
