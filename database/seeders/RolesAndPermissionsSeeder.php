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
            'escola.configurar',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Admin da escola: acesso completo + gestão de usuários
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // Coordenador: acesso completo (sem gestão de usuários)
        $coordenador = Role::firstOrCreate(['name' => 'coordenador']);
        $coordenador->syncPermissions(array_diff($permissions, ['usuarios.criar', 'usuarios.editar']));

        // Orientador pedagógico: acesso completo (sem gestão de usuários)
        $orientador = Role::firstOrCreate(['name' => 'orientador']);
        $orientador->syncPermissions(array_diff($permissions, ['usuarios.criar', 'usuarios.editar']));

        // Professor: apenas PEI
        $professor = Role::firstOrCreate(['name' => 'professor']);
        $professor->syncPermissions([
            'alunos.ver',
            'pei.ver', 'pei.criar', 'pei.editar',
            'observacoes.criar',
        ]);

    }
}