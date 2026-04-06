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
            // Alunos
            'alunos.ver', 'alunos.criar', 'alunos.editar', 'alunos.deletar',
            // Turmas
            'turmas.ver', 'turmas.gerenciar',
            // Documentos
            'documentos.ver_todos',
            'pei.ver', 'pei.criar', 'pei.editar',
            'paee.ver', 'paee.criar', 'paee.editar',
            'estudo_caso.ver', 'estudo_caso.criar', 'estudo_caso.editar',
            // Laudos & Observações
            'laudos.anexar',
            'observacoes.criar',
            // Usuários
            'usuarios.ver', 'usuarios.criar', 'usuarios.editar', 'usuarios.deletar',
            // Matérias
            'materias.gerenciar',
            // Relatórios & Configuração
            'relatorios.exportar',
            'escola.configurar',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Admin da escola: acesso completo + gestão de usuários
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // Coordenador: acesso completo exceto configuração de escola e gestão de usuários
        $coordenador = Role::firstOrCreate(['name' => 'coordenador']);
        $coordenador->syncPermissions(array_diff($permissions, [
            'usuarios.criar', 'usuarios.editar', 'usuarios.deletar',
            'escola.configurar',
        ]));

        // Orientador: foco pedagógico — sem usuários e sem turmas/matérias
        $orientador = Role::firstOrCreate(['name' => 'orientador']);
        $orientador->syncPermissions(array_diff($permissions, [
            'usuarios.ver', 'usuarios.criar', 'usuarios.editar', 'usuarios.deletar',
            'turmas.gerenciar',
            'materias.gerenciar',
            'escola.configurar',
        ]));

        // Professor: apenas visualizar alunos, criar/editar PEI e observações
        $professor = Role::firstOrCreate(['name' => 'professor']);
        $professor->syncPermissions([
            'alunos.ver',
            'turmas.ver',
            'pei.ver', 'pei.criar', 'pei.editar',
            'observacoes.criar',
        ]);

    }
}