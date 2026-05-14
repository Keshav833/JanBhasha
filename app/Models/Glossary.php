<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Glossary extends Model
{
    use HasFactory;

    protected $fillable = [
        'organisation_id',
        'source_term',
        'target_term',
        'case_sensitive',
        'notes',
    ];

    protected $casts = [
        'case_sensitive' => 'boolean',
    ];

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
