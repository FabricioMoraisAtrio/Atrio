<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title', 'body', 'level', 'audience', 'school_id',
        'active', 'starts_at', 'ends_at', 'admin_user_id',
    ];

    protected function casts(): array
    {
        return [
            'active'    => 'boolean',
            'starts_at' => 'date',
            'ends_at'   => 'date',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /** Avisos ativos dentro da janela de datas. */
    public function scopeActiveNow(Builder $q): Builder
    {
        $today = now()->toDateString();
        return $q->where('active', true)
            ->where(fn ($w) => $w->whereNull('starts_at')->orWhereDate('starts_at', '<=', $today))
            ->where(fn ($w) => $w->whereNull('ends_at')->orWhereDate('ends_at', '>=', $today));
    }

    /** Direcionados a uma escola (todos OU específicos dela). */
    public function scopeForSchool(Builder $q, int $schoolId): Builder
    {
        return $q->where(fn ($w) => $w->where('audience', 'all')
            ->orWhere(fn ($s) => $s->where('audience', 'school')->where('school_id', $schoolId)));
    }
}
