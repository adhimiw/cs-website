<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'tags',
        'type',
        'order',
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}
