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
            // Jornada Alimentar
            'seletividade.ver',
            'seletividade.gerenciar',
            // Adaptações para Prova
            'adaptacoes.ver',
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

        // Orientador: foco pedagógico — sem usuários, sem turmas, sem configuração
        $orientador = Role::firstOrCreate(['name' => 'orientador']);
        $orientador->syncPermissions(array_diff($permissions, [
            'usuarios.ver', 'usuarios.criar', 'usuarios.editar', 'usuarios.deletar',
            'turmas.gerenciar',
            'escola.configurar',
            'relatorios.exportar',
        ]));

        // Professor: visualizar alunos/turmas, PEI, observações e leitura de Jornada/Adaptações
        $professor = Role::firstOrCreate(['name' => 'professor']);
        $professor->syncPermissions([
            'alunos.ver',
            'turmas.ver',
            'pei.ver', 'pei.criar', 'pei.editar',
            'observacoes.criar',
            'seletividade.ver',
            'adaptacoes.ver',
        ]);

    }
}