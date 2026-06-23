<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentAccessLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'school_id', 'document_id', 'student_id', 'user_id', 'action',
        'document_type', 'document_year', 'student_name', 'ip', 'accessed_at',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    /**
     * Registra um evento de auditoria para entidades que não são documentos
     * (alunos, laudos, usuários). Eventos de documento continuam sendo gravados
     * pelos hooks do Document e pelos controllers de exportação.
     *
     * $attributes informa o alvo: student_id, student_name e document_type
     * (marcador da entidade: 'aluno' | 'laudo' | 'usuario').
     */
    public static function record(string $action, array $attributes = []): void
    {
        static::create(array_merge([
            'school_id'     => session('school_id'),
            'document_id'   => null,
            'student_id'    => null,
            'user_id'       => auth()->id(),
            'action'        => $action,
            'document_type' => null,
            'document_year' => null,
            'student_name'  => null,
            'ip'            => request()->ip(),
            'accessed_at'   => now(),
        ], $attributes));
    }

    protected function casts(): array
    {
        return ['accessed_at' => 'datetime'];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}