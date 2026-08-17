<?php

use App\Models\SchoolRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /** Permissões de acesso às rotinas (visibilidade no menu), geridas por perfil. */
    private array $perms = [
        'rotina.painel', 'rotina.turmas', 'rotina.alunos', 'rotina.documentos', 'rotina.adaptacoes',
        'rotina.reunioes', 'rotina.linha_do_tempo', 'rotina.seletividade', 'rotina.usuarios',
    ];

    /**
     * Cria as permissões e — para NÃO regredir — concede todas as rotinas aos papéis
     * internos e a todos os perfis já existentes (que hoje enxergam tudo por módulo).
     * A partir daqui o superadmin desmarca o que não quiser em cada perfil.
     */
    public function up(): void
    {
        foreach ($this->perms as $p) {
            Permission::findOrCreate($p, 'web');
        }
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['admin', 'coordenador', 'orientador', 'professor'] as $name) {
            if ($role = Role::where('name', $name)->where('guard_name', 'web')->first()) {
                $role->givePermissionTo($this->perms);
            }
        }

        foreach (SchoolRole::withTrashed()->get() as $sr) {
            $merged = array_values(array_unique(array_merge((array) $sr->permissions, $this->perms)));
            DB::table('school_roles')->where('id', $sr->id)->update(['permissions' => json_encode($merged)]);

            if ($sr->spatie_role && ($role = Role::where('name', $sr->spatie_role)->where('guard_name', 'web')->first())) {
                $role->givePermissionTo($this->perms);
            }
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::whereIn('name', $this->perms)->delete();
    }
};
