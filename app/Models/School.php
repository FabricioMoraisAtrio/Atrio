<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name', 'slug', 'is_active', 'plan', 'plan_status',
        'plan_expires_at', 'max_students', 'monthly_fee', 'notes', 'logo', 'theme_color', 'modules',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'plan_expires_at' => 'date',
            'monthly_fee'     => 'decimal:2',
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
            'painel'         => 'Painel de Controle',
            'alunos'         => 'Estudantes',
            'documentos'     => 'Documentos de Inclusão',
            'reunioes'       => 'Reuniões / Atas',
            'linha_do_tempo' => 'Linha do Tempo',
            'turmas'         => 'Turmas',
            'adaptacoes'     => 'Adaptações para Prova',
            'materias'       => 'Matérias',
            'usuarios'       => 'Usuários',
            'configuracoes'  => 'Configurações',
            'seletividade'   => 'Jornada Alimentar',
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

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function isExpired(): bool
    {
        return $this->plan_expires_at && $this->plan_expires_at->isPast();
    }

}