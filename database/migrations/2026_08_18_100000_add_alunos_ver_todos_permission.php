<?php

use App\Models\SchoolRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Permissão de escopo: quem tem `alunos.ver_todos` enxerga TODOS os estudantes da
     * escola; quem não tem (professor) fica restrito aos das próprias turmas.
     *
     * Para NÃO regredir: concede a permissão aos papéis internos que hoje veem tudo
     * (admin/coordenador/orientador) e a todos os perfis customizados que já têm
     * `alunos.ver` (hoje enxergam a escola inteira). O papel `professor` fica de fora
     * de propósito — é o único que passa a ser escopado.
     */
    public function up(): void
    {
        Permission::findOrCreate('alunos.ver_todos', 'web');
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['admin', 'coordenador', 'orientador'] as $name) {
            if ($role = Role::where('name', $name)->where('guard_name', 'web')->first()) {
                $role->givePermissionTo('alunos.ver_todos');
            }
        }

        foreach (SchoolRole::withTrashed()->get() as $sr) {
            // Perfis de sistema (admin já tratado acima; professor é o único escopado) ficam de fora.
            if ($sr->is_system) {
                continue;
            }
            // Só quem já via os estudantes (tinha alunos.ver) preserva a visão total.
            if (! in_array('alunos.ver', (array) $sr->permissions, true)) {
                continue;
            }

            $merged = array_values(array_unique(array_merge((array) $sr->permissions, ['alunos.ver_todos'])));
            DB::table('school_roles')->where('id', $sr->id)->update(['permissions' => json_encode($merged)]);

            if ($sr->spatie_role && ($role = Role::where('name', $sr->spatie_role)->where('guard_name', 'web')->first())) {
                $role->givePermissionTo('alunos.ver_todos');
            }
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::where('name', 'alunos.ver_todos')->delete();
    }
};
