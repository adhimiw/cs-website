<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatSession extends Model
{
    protected $fillable = [
        'session_uuid',
        'ip_address',
        'country',
        'region',
        'city',
        'referrer_url',
        'referrer_source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'landing_page',
        'is_qualified',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }
}
