<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentAcademicGoal extends Model
{
    protected $fillable = [
        'school_id', 'student_id', 'subject_id', 'categoria', 'year', 'meta', 'ordem',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /** Acompanhamento bimestral desta meta (evolução no PEI). */
    public function progressos(): HasMany
    {
        return $this->hasMany(GoalProgress::class);
    }
}
