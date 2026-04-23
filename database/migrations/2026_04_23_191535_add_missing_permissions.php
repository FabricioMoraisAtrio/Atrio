<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $new = [
            'seletividade.ver',
            'seletividade.gerenciar',
            'adaptacoes.ver',
        ];

        foreach ($new as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Admin e Coordenador recebem todas as novas permissões
        foreach (['admin', 'coordenador'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($new);
            }
        }

        // Orientador: ver e gerenciar seletividade + ver adaptações
        $orientador = Role::where('name', 'orientador')->first();
        if ($orientador) {
            $orientador->givePermissionTo(['seletividade.ver', 'seletividade.gerenciar', 'adaptacoes.ver']);
        }

        // Professor: somente visualizar (leitura)
        $professor = Role::where('name', 'professor')->first();
        if ($professor) {
            $professor->givePermissionTo(['seletividade.ver', 'adaptacoes.ver']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (['seletividade.ver', 'seletividade.gerenciar', 'adaptacoes.ver'] as $perm) {
            Permission::where('name', $perm)->delete();
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
