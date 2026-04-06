<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolRole extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id', 'name', 'slug', 'spatie_role', 'color', 'permissions', 'is_system',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_system'   => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function usersCount(): int
    {
        return User::whereHas('roles', fn($q) => $q->where('name', $this->spatie_role))->count();
    }
}
