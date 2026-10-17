<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'name',
        'code',
    ];

    protected static function booted(): void
    {
        static::creating(function ($classroom) {
            // Generate a unique 6-letter uppercase code (e.g., 'A7X9Q2')
            do {
                $code = strtoupper(Str::random(6));
            } while (self::where('code', $code)->exists());

            $classroom->code = $code;
        });
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'classroom_id', 'user_id')->withTimestamps();
    }
}