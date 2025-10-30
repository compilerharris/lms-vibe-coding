<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Project extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'alt_name',
        'description',
        'developer_user_id',
        'is_active',
        'round_robin_count',
        'last_assigned_cp_number',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'round_robin_count' => 'integer',
        'last_assigned_cp_number' => 'integer',
    ];

    /**
     * Boot method to auto-generate alt_name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->alt_name)) {
                $project->alt_name = $project->generateAltName();
            }
        });
    }

    /**
     * Generate alternative name for project
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
     * Get the developer user that owns the project.
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_user_id');
    }

    /**
     * Get the leads for the project.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
