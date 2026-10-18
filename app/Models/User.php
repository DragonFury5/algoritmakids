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
        'tier',
        'paused_at',
        'total_time_spent_seconds'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'consent_given_at' => 'datetime',
        'sound_enabled' => 'boolean',
        'paused_at' => 'datetime',
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
    public function tierConfig(): array
{
    return config('tiers.' . $this->tier) ?? config('tiers.rookie');
}

public function checkModuleCompletion(int $moduleId): ?\App\Models\Badge
{
    // All levels of this module for this user's tier
    $levelIds = \App\Models\Level::where('module_id', $moduleId)
        ->where('tier', $this->tier)
        ->pluck('id');

    if ($levelIds->isEmpty()) {
        return null;
    }

    // Did the user complete every level?
    $completed = $this->progress()
        ->whereIn('level_id', $levelIds)
        ->whereNotNull('completed_at')
        ->count();

    if ($completed < $levelIds->count()) {
        return null;
    }

    // Find this module's badge
    $badge = \App\Models\Badge::where('module_id', $moduleId)->first();
    if (! $badge) {
        return null;
    }

    // Already earned?
    if ($this->badges()->where('badge_id', $badge->id)->exists()) {
        return null;
    }

    // Award it (seen_at stays null → celebration)
    $this->badges()->attach($badge->id, [
        'earned_at' => now(),
        'seen_at'   => null,
    ]);

    return $badge;
}

public function unseenBadges()
{
    return $this->badges()
        ->wherePivotNull('seen_at')
        ->get();
}

public function markBadgesSeen(): void
{
    \DB::table('user_badges')
        ->where('user_id', $this->id)
        ->whereNull('seen_at')
        ->update(['seen_at' => now()]);
}

public function isPaused(): bool
{
    return $this->paused_at !== null;
}

public function pause(): void
{
    $this->update(['paused_at' => now()]);
}

public function resume(): void
{
    $this->update(['paused_at' => null]);
}


    // Teacher: classrooms they own
    public function classroomsOwned(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    // Student: classrooms they are enrolled in
    public function classroomsJoined(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student', 'user_id', 'classroom_id')->withTimestamps();
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

        public function getFormattedTimeSpentAttribute(): string
    {
        $seconds = $this->total_time_spent_seconds ?? 0;
        
        if ($seconds < 60) {
            return "{$seconds}s";
        }
        
        $minutes = floor($seconds / 60);
        if ($minutes < 60) {
            return "{$minutes}m";
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        return "{$hours}h {$remainingMinutes}m";
    }
}