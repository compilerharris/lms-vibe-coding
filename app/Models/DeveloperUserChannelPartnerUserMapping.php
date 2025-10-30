<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeveloperUserChannelPartnerUserMapping extends Model
{
    use HasUuids;

    protected $fillable = [
        'developer_user_id',
        'channel_partner_user_id',
        'is_active',
        'round_robin_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'round_robin_count' => 'integer',
    ];

    public function developerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_user_id');
    }

    public function channelPartnerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'channel_partner_user_id');
    }
}