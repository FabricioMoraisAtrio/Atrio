<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laudo extends Model
{
    protected $fillable = [
        'school_id', 'student_id', 'uploaded_by',
        'tipo', 'descricao', 'arquivo', 'data_laudo',
    ];

    protected $casts = [
        'data_laudo' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'medico'           => 'Médico',
            'psicologico'      => 'Psicológico',
            'fonoaudiologico'  => 'Fonoaudiológico',
            'neuropediatrico'  => 'Neuropediátrico',
            default            => 'Outro',
        };
    }
}