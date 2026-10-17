<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'slug',
        'title_en',
        'title_id',
        'instructions_en',
        'instructions_id',
        'game_type',
        'game_data',
        'order',
        'tier',
    ];

    protected $casts = [
        'game_data' => 'array',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function getTitleAttribute(): string
    {
        return $this->{'title_' . app()->getLocale()};
    }

    public function getInstructionsAttribute(): string
    {
        return $this->{'instructions_' . app()->getLocale()};
    }

    public function playRoute(): string
{
    return $this->game_type === 'robot_path'
        ? route('play.robot', $this)
        : route('play.pattern', $this);
}

public function scopeForTier($query, string $tier)
{
    return $query->where('tier', $tier);
}

public function isUnlockedFor($user): bool
{
    if (! $user) return false;

    // Find the immediately preceding level in the same module and tier
    $previousLevel = self::where('module_id', $this->module_id)
        ->where('tier', $this->tier)
        ->where('order', '<', $this->order)
        ->orderBy('order', 'desc')
        ->first();

    // If there is no previous level, it's the first one. Always unlocked.
    if (! $previousLevel) {
        return true;
    }

    // Check if the previous level is completed
    return $user->progress()
        ->where('level_id', $previousLevel->id)
        ->whereNotNull('completed_at')
        ->exists();
}
}