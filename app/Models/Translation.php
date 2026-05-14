<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organisation_id',
        'user_id',
        'source_text',
        'translated_text',
        'source_lang',
        'target_lang',
        'provider',
        'characters',
        'status',
        'error_message',
        'source_label',
        'is_cached',
    ];

    protected $casts = [
        'characters' => 'integer',
        'is_cached'  => 'boolean',
    ];

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForOrganisation($query, int $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    public function getSourcePreviewAttribute(): string
    {
        return \Str::limit($this->source_text, 80);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'completed' => ['label' => 'Completed', 'class' => 'badge-success'],
            'failed'    => ['label' => 'Failed',    'class' => 'badge-error'],
            default     => ['label' => 'Pending',   'class' => 'badge-warning'],
        };
    }
}
