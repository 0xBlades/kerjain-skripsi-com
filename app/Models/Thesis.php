<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thesis extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'topic',
        'summary',
        'status',
        'progress',
        'target_completion_date',
        'supervisor_name',
        'research_question',
        'methodology',
        'keywords',
        'last_activity_at',
        'chapter_statuses',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'keywords' => 'array',
        'chapter_statuses' => 'array',
        'target_completion_date' => 'date',
        'last_activity_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(PaperReference::class);
    }

    public function aiInteractions(): HasMany
    {
        return $this->hasMany(AIInteraction::class);
    }

    public function plagiarismChecks(): HasMany
    {
        return $this->hasMany(PlagiarismCheck::class);
    }

    /**
     * Get the progress percentage attribute.
     */
    public function getProgressPercentageAttribute(): int
    {
        return $this->progress ?? 0;
    }
}
