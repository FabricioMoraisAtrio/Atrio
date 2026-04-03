<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'school_id', 'name', 'slug', 'label_responsavel', 'tipo', 'ordem',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(SubjectInventoryItem::class)->orderBy('ordem');
    }

    public function peiSections(): HasMany
    {
        return $this->hasMany(PeiSection::class);
    }
}
