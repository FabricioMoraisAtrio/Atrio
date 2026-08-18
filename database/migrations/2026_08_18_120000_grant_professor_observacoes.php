<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Observações unificadas no portal: o professor volta a ter `observacoes.criar`
     * (a migration anterior a havia removido por precaução), agora que a rota da
     * secretaria tem guards de escopo (só estudantes das próprias turmas) e de
     * propriedade (só remove as próprias). Assim eliminamos as rotas professor.* de
     * observação.
     */
    public function up(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        if (($role = Role::where('name', 'professor')->where('guard_name', 'web')->first())
            && Permission::where('name', 'observacoes.criar')->exists()) {
            $role->givePermissionTo('observacoes.criar');
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        if ($role = Role::where('name', 'professor')->where('guard_name', 'web')->first()) {
            $role->revokePermissionTo('observacoes.criar');
        }
    }
};
