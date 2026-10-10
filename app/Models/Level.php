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
}