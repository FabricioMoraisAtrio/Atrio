<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'alunos.ver', 'alunos.criar', 'alunos.editar', 'alunos.deletar',
            'laudos.anexar',
            'usuarios.criar', 'usuarios.editar',
            'documentos.ver_todos',
            'pei.ver', 'pei.criar', 'pei.editar',
            'paee.ver', 'paee.criar', 'paee.editar',
            'estudo_caso.ver', 'estudo_caso.criar', 'estudo_caso.editar',
            'observacoes.criar',
            'relatorios.exportar',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $secretaria = Role::firstOrCreate(['name' => 'secretaria']);
        $secretaria->syncPermissions($permissions);

        $professor = Role::firstOrCreate(['name' => 'professor']);
        $professor->syncPermissions([
            'alunos.ver',
            'pei.ver', 'pei.criar', 'pei.editar',
            'paee.ver', 'paee.criar', 'paee.editar',
            'estudo_caso.ver', 'estudo_caso.criar', 'estudo_caso.editar',
            'observacoes.criar',
            'relatorios.exportar',
        ]);

        $pai = Role::firstOrCreate(['name' => 'pai']);
        $pai->syncPermissions(['alunos.ver', 'pei.ver']);
    }
}