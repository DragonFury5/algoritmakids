<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_en',
        'title_id',
        'description_en',
        'description_id',
        'order',
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class)->orderBy('order');
    }

    public function badges(): HasMany
    {
        return $this->hasMany(Badge::class);
    }

    // Helper: get title in the current locale
    public function getTitleAttribute(): string
    {
        return $this->{'title_' . app()->getLocale()};
    }

    public function getDescriptionAttribute(): string
    {
        return $this->{'description_' . app()->getLocale()};
    }
}