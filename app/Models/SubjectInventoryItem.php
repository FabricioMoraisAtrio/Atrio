<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectInventoryItem extends Model
{
    protected $fillable = ['school_id', 'subject_id', 'meta', 'categoria', 'ordem'];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
