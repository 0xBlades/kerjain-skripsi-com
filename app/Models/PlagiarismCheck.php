<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Thesis;
use App\Models\Document;
use App\Models\User;

class PlagiarismCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'document_id',
        'user_id',
        'text_excerpt',
        'similarity_score',
        'status',
        'matches',
        'report_url',
        'checked_at',
    ];

    protected $casts = [
        'matches' => 'array',
        'checked_at' => 'datetime',
        'similarity_score' => 'integer',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
