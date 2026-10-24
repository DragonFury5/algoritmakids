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

    // 1. Find the highest order number that is LESS than this level's order
    $previousOrder = self::where('module_id', $this->module_id)
        ->where('tier', $this->tier)
        ->where('order', '<', $this->order)
        ->max('order');

    // 2. If there is no previous order, it's the first level. Always unlocked.
    if (! $previousOrder) {
        return true;
    }

    // 3. Get all levels that share that previous order (handles duplicates)
    $previousLevelIds = self::where('module_id', $this->module_id)
        ->where('tier', $this->tier)
        ->where('order', $previousOrder)
        ->pluck('id');

    // 4. Check if the user has completed at least one of them
    $completedCount = $user->progress()
        ->whereIn('level_id', $previousLevelIds)
        ->whereNotNull('completed_at')
        ->count();

    return $completedCount > 0;
}
}