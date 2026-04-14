<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name', 'slug', 'is_active', 'plan', 'plan_status',
        'plan_expires_at', 'max_students', 'notes', 'logo', 'theme_color', 'modules',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'plan_expires_at' => 'date',
            'modules'         => 'array',
        ];
    }

    /** Retorna true se o módulo está habilitado para esta escola.
     *  null = todos habilitados (retrocompatível). */
    public function hasModule(string $key): bool
    {
        if ($this->modules === null) return true;
        return in_array($key, $this->modules, true);
    }

    /** Lista dos módulos disponíveis no sistema. */
    public static function availableModules(): array
    {
        return [
            'painel'      => 'Painel de Controle',
            'alunos'      => 'Alunos',
            'documentos'  => 'Documentos de Inclusão',
            'turmas'      => 'Turmas',
            'adaptacoes'  => 'Adaptações para Prova',
            'materias'    => 'Matérias',
            'usuarios'    => 'Usuários',
            'configuracoes' => 'Configurações',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function isExpired(): bool
    {
        return $this->plan_expires_at && $this->plan_expires_at->isPast();
    }

}