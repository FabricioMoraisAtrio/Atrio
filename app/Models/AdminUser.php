<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use Notifiable;

    /** Rotinas do painel (chave => rótulo). */
    public const ROUTINES = [
        'dashboard'       => 'Dashboard',
        'escolas'         => 'Cadastro de Escolas',
        'financeiro'      => 'Financeiro',
        'comunicados'     => 'Comunicados',
        'relatorios'      => 'Relatórios',
        'administradores' => 'Administradores',
        'logs'            => 'Logs / Auditoria',
    ];

    /** Rota inicial de cada rotina (para nav e redirect). */
    public const ROUTE_OF = [
        'dashboard'       => 'admin.dashboard',
        'escolas'         => 'admin.schools.index',
        'financeiro'      => 'admin.invoices.index',
        'comunicados'     => 'admin.announcements.index',
        'relatorios'      => 'admin.reports.index',
        'administradores' => 'admin.admins.index',
        'logs'            => 'admin.logs.index',
    ];

    protected $fillable = ['name', 'email', 'password', 'permissions'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'permissions' => 'array',
        ];
    }

    /** Acesso total (owner) = sem restrição definida. */
    public function hasFullAccess(): bool
    {
        return $this->permissions === null;
    }

    /** Pode acessar a rotina indicada? */
    public function canAccess(string $routine): bool
    {
        return $this->hasFullAccess() || in_array($routine, (array) $this->permissions, true);
    }

    /** Primeira rotina permitida (rota), usada como landing/redirect. */
    public function homeRoute(): string
    {
        foreach (array_keys(self::ROUTINES) as $key) {
            if ($this->canAccess($key)) {
                return self::ROUTE_OF[$key];
            }
        }
        return 'admin.dashboard';
    }
}
