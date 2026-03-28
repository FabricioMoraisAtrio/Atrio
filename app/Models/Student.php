<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
protected $fillable = [
    'school_id', 'name', 'registration_number',
    'birth_date', 'is_atypical', 'condition', 'has_case_study',
    'cid_autismo', 'cid_tdah', 'cid_down',
    'cid_deficiencia_intelectual', 'cid_deficiencia_visual',
    'cid_deficiencia_auditiva', 'cid_outros',
];
protected function casts(): array
{
    return [
        'birth_date'                 => 'date',
        'is_atypical'                => 'boolean',
        'has_case_study'             => 'boolean',
        'cid_autismo'                => 'boolean',
        'cid_tdah'                   => 'boolean',
        'cid_down'                   => 'boolean',
        'cid_deficiencia_intelectual'=> 'boolean',
        'cid_deficiencia_visual'     => 'boolean',
        'cid_deficiencia_auditiva'   => 'boolean',
        'cid_outros'                 => 'boolean',
    ];
}

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'school_class_student')
                    ->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_user')
                    ->withTimestamps();
    }
    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class)->latest();
    }

    public function laudos(): HasMany
    {
        return $this->hasMany(Laudo::class)->latest();
    }

}