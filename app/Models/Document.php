<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
    'school_id', 'student_id', 'author_id', 'type', 'year', 'status', 'content',
];

    protected function casts(): array
    {
        return ['content' => 'array'];
    }

   protected static function booted(): void
{
    static::addGlobalScope(new \App\Scopes\SchoolScope());

    static::saved(function ($doc) {
        \Illuminate\Support\Facades\Cache::forget('pendentes_count_' . $doc->school_id);
    });

    static::deleted(function ($doc) {
        \Illuminate\Support\Facades\Cache::forget('pendentes_count_' . $doc->school_id);
    });

    static::created(function ($doc) {
        DocumentAccessLog::create([
            'school_id'     => $doc->school_id,
            'document_id'   => $doc->id,
            'student_id'    => $doc->student_id,
            'user_id'       => auth()->id(),
            'action'        => 'created',
            'document_type' => $doc->type,
            'document_year' => $doc->year,
            'student_name'  => $doc->student?->name,
            'ip'            => request()->ip(),
            'accessed_at'   => now(),
        ]);
    });

    static::updated(function ($doc) {
        DocumentAccessLog::create([
            'school_id'     => $doc->school_id,
            'document_id'   => $doc->id,
            'student_id'    => $doc->student_id,
            'user_id'       => auth()->id(),
            'action'        => 'edited',
            'document_type' => $doc->type,
            'document_year' => $doc->year,
            'student_name'  => $doc->student?->name,
            'ip'            => request()->ip(),
            'accessed_at'   => now(),
        ]);
    });

    static::deleting(function ($doc) {
        DocumentAccessLog::create([
            'school_id'     => $doc->school_id,
            'document_id'   => $doc->id,
            'student_id'    => $doc->student_id,
            'user_id'       => auth()->id(),
            'action'        => 'deleted',
            'document_type' => $doc->type,
            'document_year' => $doc->year,
            'student_name'  => $doc->student?->name,
            'ip'            => request()->ip(),
            'accessed_at'   => now(),
        ]);
    });
}
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(DocumentAccessLog::class);
    }
    
    
}