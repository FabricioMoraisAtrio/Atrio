<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentAccessLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'school_id', 'document_id', 'student_id', 'user_id', 'action',
        'document_type', 'document_year', 'student_name', 'ip', 'accessed_at',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    protected function casts(): array
    {
        return ['accessed_at' => 'datetime'];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}