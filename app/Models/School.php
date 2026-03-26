<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name', 'slug', 'is_active', 'plan', 'plan_status',
        'plan_expires_at', 'max_students', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'plan_expires_at' => 'date',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function isExpired(): bool
    {
        return $this->plan_expires_at && $this->plan_expires_at->isPast();
    }
}