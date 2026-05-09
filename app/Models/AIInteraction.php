<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Thesis;

class AIInteraction extends Model
{
    use HasFactory;

    protected $table = 'ai_interactions';

    protected $fillable = [
        'user_id',
        'thesis_id',
        'feature',
        'prompt',
        'response',
        'metadata',
        'duration_ms',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
        'duration_ms' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }
}
