<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /** Condições que enquadram o aluno como Público Alvo da Educação Especial. */
    public const PUBLICO_ALVO_FIELDS = [
        'cid_autismo', 'cid_tgd', 'cid_down', 'cid_ahsd',
        'cid_deficiencia_intelectual', 'cid_deficiencia_visual',
        'cid_deficiencia_auditiva', 'cid_dfm',
    ];

    /** Níveis da Taxonomia de Bloom (revisada), do mais básico ao mais complexo. */
    public const NIVEIS_BLOOM = [
        'lembrar'  => 'Lembrar',
        'entender' => 'Entender',
        'aplicar'  => 'Aplicar',
        'analisar' => 'Analisar',
        'avaliar'  => 'Avaliar',
        'criar'    => 'Criar',
    ];

protected $fillable = [
    'school_id', 'name', 'photo', 'registration_number',
    'birth_date',
    'responsavel_nome', 'responsavel_email', 'responsavel_telefone',
    'responsavel_2_nome', 'responsavel_2_email', 'responsavel_2_telefone',
    'is_atypical', 'condition', 'has_case_study', 'nivel_bloom',
    'tea_nivel_suporte',
    'cid_autismo', 'cid_tgd', 'cid_tdah', 'cid_down',
    'cid_deficiencia_intelectual', 'cid_deficiencia_visual',
    'cid_deficiencia_auditiva', 'cid_outros',
    'cid_ahsd', 'cid_dalt', 'cid_dfm', 'cid_disl', 'cid_epile',
    'cid_mc', 'cid_mult', 'cid_si', 'cid_tag', 'cid_tda',
    'cid_tdc', 'cid_tdl', 'cid_tod', 'cid_tpac', 'cid_tps', 'cid_th',
];
protected function casts(): array
{
    return [
        'birth_date'                  => 'date',
        'is_atypical'                 => 'boolean',
        'has_case_study'              => 'boolean',
        'cid_autismo'                 => 'boolean',
        'cid_tgd'                     => 'boolean',
        'cid_tdah'                    => 'boolean',
        'cid_down'                    => 'boolean',
        'cid_deficiencia_intelectual' => 'boolean',
        'cid_deficiencia_visual'      => 'boolean',
        'cid_deficiencia_auditiva'    => 'boolean',
        'cid_outros'                  => 'boolean',
        'cid_ahsd'                    => 'boolean',
        'cid_dalt'                    => 'boolean',
        'cid_dfm'                     => 'boolean',
        'cid_disl'                    => 'boolean',
        'cid_epile'                   => 'boolean',
        'cid_mc'                      => 'boolean',
        'cid_mult'                    => 'boolean',
        'cid_si'                      => 'boolean',
        'cid_tag'                     => 'boolean',
        'cid_tda'                     => 'boolean',
        'cid_tdc'                     => 'boolean',
        'cid_tdl'                     => 'boolean',
        'cid_tod'                     => 'boolean',
        'cid_tpac'                    => 'boolean',
        'cid_tps'                     => 'boolean',
        'cid_th'                      => 'boolean',
    ];
}

    /** Retorna true se o aluno se enquadra como Público Alvo da Educação Especial. */
    public function getIsPublicoAlvoAttribute(): bool
    {
        foreach (self::PUBLICO_ALVO_FIELDS as $field) {
            if ($this->$field) return true;
        }
        return false;
    }

    /** Rótulo do nível de Bloom do estudante (ou null se não definido). */
    public function getNivelBloomLabelAttribute(): ?string
    {
        return self::NIVEIS_BLOOM[$this->nivel_bloom] ?? null;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope());

        static::saved(function ($student) {
            \Illuminate\Support\Facades\Cache::forget('pendentes_count_' . $student->school_id);
        });

        static::deleted(function ($student) {
            \Illuminate\Support\Facades\Cache::forget('pendentes_count_' . $student->school_id);
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'school_class_student')
                    ->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_user')
                    ->withTimestamps();
    }
    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class)->latest();
    }

    public function laudos(): HasMany
    {
        return $this->hasMany(Laudo::class)->latest();
    }

    public function foodItems(): HasMany
    {
        return $this->hasMany(StudentFoodItem::class);
    }

    public function academicGoals(): HasMany
    {
        return $this->hasMany(StudentAcademicGoal::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function bimestreClosings(): HasMany
    {
        return $this->hasMany(BimestreClosing::class);
    }

}