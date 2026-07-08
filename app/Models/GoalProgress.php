<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalProgress extends Model
{
    // "progress" é incontável no inflector do Laravel (resolveria para goal_progress).
    protected $table = 'goal_progresses';

    protected $fillable = [
        'school_id', 'student_academic_goal_id', 'year', 'bimestre',
        'status', 'observacao', 'evaluated_by',
    ];

    public const STATUSES = [
        'nao_avaliado' => 'Não avaliado',
        'nao_atingiu'  => 'Não atingiu',
        'em_progresso' => 'Em progresso',
        'atingiu'      => 'Atingiu',
    ];

    /** Peso 0..1 para o gráfico de evolução. */
    public const SCORES = [
        'nao_atingiu'  => 0.0,
        'em_progresso' => 0.5,
        'atingiu'      => 1.0,
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(StudentAcademicGoal::class, 'student_academic_goal_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
