<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Thesis;
use App\Models\User;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'user_id',
        'label',
        'type',
        'original_name',
        'storage_path',
        'mime_type',
        'size',
        'notes',
        'last_reviewed_at',
    ];

    protected $casts = [
        'size' => 'integer',
        'last_reviewed_at' => 'datetime',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
