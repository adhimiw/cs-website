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
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
