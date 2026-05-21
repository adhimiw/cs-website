<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = [
        'page',
        'section',
        'key',
        'value',
        'type',
    ];

    protected static function booted()
    {
        static::saved(function ($siteContent) {
            \App\Services\ContentService::clearCache(
                $siteContent->page,
                $siteContent->section,
                $siteContent->key
            );
        });
    }
}
