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

    public function academicGoals(): HasMany
    {
        return $this->hasMany(StudentAcademicGoal::class);
    }

    /**
     * Matérias padrão (grade BNCC do Ensino Fundamental + campo do professor regente).
     * Usado ao criar uma escola e no backfill de escolas existentes.
     */
    public const DEFAULTS = [
        ['name' => 'Língua Portuguesa', 'slug' => 'lingua-portuguesa', 'label_responsavel' => 'Prof. Língua Portuguesa', 'tipo' => 'disciplina'],
        ['name' => 'Matemática',        'slug' => 'matematica',        'label_responsavel' => 'Prof. Matemática',        'tipo' => 'disciplina'],
        ['name' => 'Ciências',          'slug' => 'ciencias',          'label_responsavel' => 'Prof. Ciências',          'tipo' => 'disciplina'],
        ['name' => 'História',          'slug' => 'historia',          'label_responsavel' => 'Prof. História',          'tipo' => 'disciplina'],
        ['name' => 'Geografia',         'slug' => 'geografia',         'label_responsavel' => 'Prof. Geografia',         'tipo' => 'disciplina'],
        ['name' => 'Arte',              'slug' => 'arte',              'label_responsavel' => 'Prof. Arte',              'tipo' => 'disciplina'],
        ['name' => 'Educação Física',   'slug' => 'educacao-fisica',   'label_responsavel' => 'Prof. Educação Física',   'tipo' => 'disciplina'],
        ['name' => 'Língua Inglesa',    'slug' => 'lingua-inglesa',    'label_responsavel' => 'Prof. Língua Inglesa',    'tipo' => 'disciplina'],
        ['name' => 'Ensino Religioso',  'slug' => 'ensino-religioso',  'label_responsavel' => 'Prof. Ensino Religioso',  'tipo' => 'disciplina'],
        ['name' => 'Professor Regente', 'slug' => 'professor-regente', 'label_responsavel' => 'Professor Regente',       'tipo' => 'regente'],
    ];

    /**
     * Semeia as matérias padrão para uma escola que ainda não possui matérias.
     */
    public static function seedDefaultsForSchool(int $schoolId): void
    {
        $existe = static::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', $schoolId)
            ->exists();

        if ($existe) {
            return;
        }

        foreach (self::DEFAULTS as $i => $materia) {
            static::withoutGlobalScope(SchoolScope::class)->create(array_merge($materia, [
                'school_id' => $schoolId,
                'ordem'     => $i + 1,
            ]));
        }
    }
}
