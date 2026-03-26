<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SchoolClass extends Model
{
    protected $fillable = ['school_id', 'name', 'shift', 'year'];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'school_class_user')
                    ->withPivot('subject')
                    ->withTimestamps();
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'school_class_student')
                    ->withTimestamps();
    }
}