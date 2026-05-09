<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Thesis;
use App\Models\User;

class PaperReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'thesis_id',
        'external_id',
        'title',
        'authors',
        'abstract',
        'publisher',
        'publication_type',
        'published_at',
        'url',
        'metadata',
        'is_favorited',
    ];

    protected $casts = [
        'published_at' => 'date',
        'metadata' => 'array',
        'is_favorited' => 'boolean',
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
