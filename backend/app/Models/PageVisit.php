<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'ip_address',
        'url',
        'referrer_url',
        'referrer_source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'country',
        'city',
    ];
}
