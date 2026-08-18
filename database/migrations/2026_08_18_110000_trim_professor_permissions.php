<?php

use App\Models\SchoolRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Portal unificado: o professor passa a usar as rotas da secretaria (escopado).
     * Suas permissões de documento/observação eram inertes no modelo antigo (as rotas
     * do professor usavam role:professor, não can:) — agora destravariam rotas de
     * documento da secretaria SEM escopo. Removemos essas permissões do papel
     * `professor`; as ações de escrita do professor seguem nas rotas professor.*
     * gated por papel. Não mexe em outros papéis.
     */
    private array $remover = ['pei.ver', 'pei.criar', 'pei.editar', 'observacoes.criar'];

    public function up(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        if ($role = Role::where('name', 'professor')->where('guard_name', 'web')->first()) {
            $role->revokePermissionTo(
                array_filter($this->remover, fn ($p) => \Spatie\Permission\Models\Permission::where('name', $p)->exists())
            );
        }

        // Perfis de sistema "professor" das escolas (spatie_role = professor): limpa o JSON
        // para o formulário de perfis refletir o novo conjunto.
        foreach (SchoolRole::withTrashed()->where('spatie_role', 'professor')->get() as $sr) {
            $novo = array_values(array_diff((array) $sr->permissions, $this->remover));
            DB::table('school_roles')->where('id', $sr->id)->update(['permissions' => json_encode($novo)]);
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Reversível: devolve as permissões ao papel professor.
        if ($role = Role::where('name', 'professor')->where('guard_name', 'web')->first()) {
            $role->givePermissionTo(
                array_filter($this->remover, fn ($p) => \Spatie\Permission\Models\Permission::where('name', $p)->exists())
            );
        }
    }
};
