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
            'alunos.ver', 'alunos.ver_todos', 'alunos.criar', 'alunos.editar', 'alunos.deletar',
            // Turmas
            'turmas.ver', 'turmas.gerenciar',
            // Documentos
            'documentos.ver_todos',
            'pei.ver', 'pei.criar', 'pei.editar',
            'pei.metas_gerenciar',
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

        // Professor: leitura escopada às próprias turmas no portal unificado. As ações
        // de escrita (seção do PEI por matéria, observações) vivem em rotas próprias
        // gated por papel (professor.*), não por permissão — por isso NÃO recebe
        // pei.*/observacoes.criar aqui (evita destravar rotas de documento da secretaria).
        // rotina.painel/turmas são criadas na migration de rotinas e liberam o menu.
        $professor = Role::firstOrCreate(['name' => 'professor']);
        $professorPerms = ['alunos.ver', 'turmas.ver', 'seletividade.ver', 'adaptacoes.ver'];
        foreach (['rotina.painel', 'rotina.turmas'] as $rp) {
            if (Permission::where('name', $rp)->exists()) {
                $professorPerms[] = $rp;
            }
        }
        $professor->syncPermissions($professorPerms);

    }
}