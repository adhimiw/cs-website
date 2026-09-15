<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'chat_session_id',
        'name',
        'email',
        'phone',
        'company',
        'meeting_type',
        'scheduled_date',
        'scheduled_time',
        'timezone',
        'topic',
        'status',
        'notes',
        'customer_notified_at',
        'team_notified_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'customer_notified_at' => 'datetime',
        'team_notified_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }
}
