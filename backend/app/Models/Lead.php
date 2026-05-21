<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    protected $fillable = [
        'chat_session_id',
        'source_type',
        'name',
        'email',
        'phone',
        'company',
        'project_type',
        'plan_or_idea',
        'budget',
        'timeline',
        'lead_status',
        'ip_address',
        'country',
        'city',
        'referrer_url',
        'referrer_source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'notes',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}
