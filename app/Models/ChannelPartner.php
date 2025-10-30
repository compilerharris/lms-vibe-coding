<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class ChannelPartner extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'developer_id',
        'is_active',
        'round_robin_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-assign round_robin_count
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($channelPartner) {
            if (empty($channelPartner->round_robin_count)) {
                $maxCount = self::where('developer_id', $channelPartner->developer_id)
                    ->max('round_robin_count') ?? 0;
                $channelPartner->round_robin_count = $maxCount + 1;
            }
        });
    }

    /**
     * Get the developer that owns the channel partner.
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    /**
     * Get the leads for the channel partner.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get the next channel partner in round-robin rotation.
     */
    public static function getNextInRoundRobin($developerId)
    {
        return self::where('developer_id', $developerId)
            ->where('is_active', true)
            ->orderBy('round_robin_count', 'asc')
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * Move to next position in round-robin rotation.
     */
    public function moveToNextPosition()
    {
        $totalCPs = self::where('developer_id', $this->developer_id)
            ->where('is_active', true)
            ->count();
        
        $this->update([
            'round_robin_count' => $this->round_robin_count + $totalCPs
        ]);
    }

    /**
     * Get the mapped developers for the channel partner.
     */
    public function mappedDevelopers(): BelongsToMany
    {
        return $this->belongsToMany(Developer::class, 'developer_channel_partner_mappings')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Get the active mapped developers for the channel partner.
     */
    public function activeMappedDevelopers(): BelongsToMany
    {
        return $this->mappedDevelopers()->wherePivot('is_active', true);
    }
}
