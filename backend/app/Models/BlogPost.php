<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'author',
        'published_at',
        'seo_meta',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo_meta' => 'array',
    ];
}
