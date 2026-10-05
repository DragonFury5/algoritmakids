<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'language',
        'parent_id',
        'consent_given_at',
        'sound_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'consent_given_at' => 'datetime',
        'sound_enabled' => 'boolean',
    ];

    // ----- Relationships -----

    // For students: the parent account they belong to
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // For parents: all their children (student accounts)
    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // For students: all their progress records
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    // For students: all badges earned
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    // ----- Helpers -----

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Total stars earned (only for students)
    public function getTotalStarsAttribute(): int
    {
        return (int) $this->progress()->sum('stars');
    }
}