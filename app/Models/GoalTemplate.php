<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;

class GoalTemplate extends Model
{
    protected $fillable = [
        'school_id', 'categoria', 'texto', 'tag', 'ordem',
    ];

    /** Categorias de metas — espelham as de StudentAcademicGoal. */
    public const CATEGORIES = [
        'academica'      => 'Acadêmicas',
        'socioemocional' => 'Socioemocionais',
        'funcional'      => 'Funcionais',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());
    }
}
