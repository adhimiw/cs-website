<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'form_name',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'payload',
        'ip_address',
        'country',
        'city',
        'referrer_url',
        'referrer_source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'thank_you_sent',
        'admin_notified',
    ];

    protected $casts = [
        'payload' => 'array',
        'thank_you_sent' => 'boolean',
        'admin_notified' => 'boolean',
    ];
}
