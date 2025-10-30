<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeveloperChannelPartnerMapping extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'developer_id',
        'channel_partner_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the developer that owns the mapping.
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    /**
     * Get the channel partner that owns the mapping.
     */
    public function channelPartner(): BelongsTo
    {
        return $this->belongsTo(ChannelPartner::class);
    }
}