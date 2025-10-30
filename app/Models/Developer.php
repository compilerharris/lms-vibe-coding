<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
class Developer extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'alt_name',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate alt_name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($developer) {
            if (empty($developer->alt_name)) {
                $developer->alt_name = $developer->generateAltName();
            }
        });
    }

    /**
     * Generate alternative name for developer
     */
    public function generateAltName()
    {
        $namePrefix = strtoupper(substr($this->name, 0, 3));
        $randomSuffix = strtoupper(Str::random(6));
        $altName = $namePrefix . $randomSuffix;

        // Ensure uniqueness
        while (static::where('alt_name', $altName)->exists()) {
            $randomSuffix = strtoupper(Str::random(6));
            $altName = $namePrefix . $randomSuffix;
        }

        return $altName;
    }

    /**
     * Get the users for the developer.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the projects for the developer.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the channel partners for the developer.
     */
    public function channelPartners(): HasMany
    {
        return $this->hasMany(ChannelPartner::class);
    }

    /**
     * Get the mapped channel partners for the developer.
     */
    public function mappedChannelPartners(): BelongsToMany
    {
        return $this->belongsToMany(ChannelPartner::class, 'developer_channel_partner_mappings')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Get the active mapped channel partners for the developer.
     */
    public function activeMappedChannelPartners(): BelongsToMany
    {
        return $this->mappedChannelPartners()->wherePivot('is_active', true);
    }
}
