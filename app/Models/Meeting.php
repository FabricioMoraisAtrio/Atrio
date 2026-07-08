<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [
        'school_id', 'student_id', 'data', 'tipo',
        'participantes', 'pauta', 'encaminhamentos', 'observacoes', 'created_by',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    /** Tipos de reunião disponíveis. */
    public const TIPOS = [
        'pei'        => 'Reunião de PEI',
        'familia'    => 'Reunião com a família',
        'equipe'     => 'Reunião de equipe',
        'devolutiva' => 'Devolutiva',
        'outro'      => 'Outro',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
