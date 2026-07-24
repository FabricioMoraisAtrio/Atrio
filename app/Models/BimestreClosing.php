<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BimestreClosing extends Model
{
    protected $fillable = [
        'school_id', 'student_id', 'year', 'bimestre', 'snapshot', 'closed_by',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
