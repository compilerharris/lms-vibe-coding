<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'alt_name',
        'cp_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate alt_name for developer users
        static::creating(function ($user) {
            if ($user->role_id && $user->role && $user->role->name === 'developer') {
                if (empty($user->alt_name)) {
                    $user->alt_name = $user->generateAltName();
                }
            }
        });

        // When a user is being deleted, also delete related mapping entries
        static::deleting(function ($user) {
            // Delete developer-channel partner mappings where this user is either developer or channel partner
            // Note: This is redundant since we have onDelete('cascade') in the migration,
            // but it's good to have explicit cleanup for clarity
            \App\Models\DeveloperUserChannelPartnerUserMapping::where('developer_user_id', $user->id)
                ->orWhere('channel_partner_user_id', $user->id)
                ->delete();
                
            // Log the deletion for audit purposes
            \Log::info('User deleted with cascading cleanup', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'role' => $user->role ? $user->role->name : 'unknown'
            ]);
        });
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get projects for developer users.
     */
    public function projects()
    {
        return $this->hasMany(\App\Models\Project::class, 'developer_user_id');
    }

    /**
     * Generate a unique alt_name for developer users.
     */
    public function generateAltName()
    {
        $namePrefix = strtoupper(substr($this->name, 0, 3));
        $randomSuffix = strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6));
        $altName = $namePrefix . $randomSuffix;
        
        // Ensure uniqueness
        while (static::where('alt_name', $altName)->exists()) {
            $randomSuffix = strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6));
            $altName = $namePrefix . $randomSuffix;
        }
        
        return $altName;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is leader.
     */
    public function isLeader()
    {
        return $this->hasRole('leader');
    }

    /**
     * Check if user is developer.
     */
    public function isDeveloper()
    {
        return $this->hasRole('developer');
    }

    /**
     * Check if user is channel partner.
     */
    public function isChannelPartner()
    {
        return $this->hasRole('channel_partner');
    }

    /**
     * Check if user is CS.
     */
    public function isCS()
    {
        return $this->hasRole('cs');
    }

    /**
     * Check if user is biddable.
     */
    public function isBiddable()
    {
        return $this->hasRole('biddable');
    }

    /**
     * Get assigned leads for channel partner users.
     */
    public function assignedLeads()
    {
        return $this->hasMany(\App\Models\Lead::class, 'assigned_user_id');
    }

    /**
     * Get mapped channel partners for developer users.
     */
    public function mappedChannelPartners()
    {
        return $this->belongsToMany(User::class, 'developer_user_channel_partner_user_mappings', 'developer_user_id', 'channel_partner_user_id')
            ->wherePivot('is_active', true)
            ->whereHas('role', function($query) {
                $query->where('name', 'channel_partner');
            });
    }

    /**
     * Get mapped developers for channel partner users.
     */
    public function mappedDevelopers()
    {
        return $this->belongsToMany(User::class, 'developer_user_channel_partner_user_mappings', 'channel_partner_user_id', 'developer_user_id')
            ->wherePivot('is_active', true)
            ->whereHas('role', function($query) {
                $query->where('name', 'developer');
            });
    }
}
