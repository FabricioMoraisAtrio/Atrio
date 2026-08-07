<?php

namespace App\Http\Controllers\Secretaria\Config;

use App\Http\Controllers\Controller;
use App\Models\SchoolRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SchoolRoleController extends Controller
{
    /** Permissões disponíveis agrupadas por categoria */
    public static function permissionGroups(): array
    {
        return [
            'Estudantes' => [
                'alunos.ver'    => 'Visualizar estudantes',
                'alunos.criar'  => 'Cadastrar estudantes',
                'alunos.editar' => 'Editar estudantes',
                'alunos.deletar'=> 'Remover estudantes',
            ],
            'Turmas' => [
                'turmas.ver'      => 'Visualizar turmas',
                'turmas.gerenciar'=> 'Criar, editar e remover turmas',
            ],
            'Documentos' => [
                'documentos.ver_todos'    => 'Ver todos os documentos',
                'pei.ver'                 => 'Visualizar PEI',
                'pei.criar'               => 'Criar PEI',
                'pei.editar'              => 'Editar PEI',
                'pei.metas_gerenciar'     => 'Gerenciar metas do PEI (acadêmicas por estudante)',
                'paee.ver'                => 'Visualizar PAEE',
                'paee.criar'              => 'Criar PAEE',
                'paee.editar'             => 'Editar PAEE',
                'estudo_caso.ver'         => 'Visualizar Estudo de Caso',
                'estudo_caso.criar'       => 'Criar Estudo de Caso',
                'estudo_caso.editar'      => 'Editar Estudo de Caso',
            ],
            'Laudos & Observações' => [
                'laudos.anexar'    => 'Anexar laudos',
                'observacoes.criar'=> 'Registrar observações',
            ],
            'Usuários' => [
                'usuarios.ver'    => 'Visualizar usuários',
                'usuarios.criar'  => 'Cadastrar usuários',
                'usuarios.editar' => 'Editar usuários',
                'usuarios.deletar'=> 'Remover usuários',
            ],
            'Jornada Alimentar' => [
                'seletividade.ver'      => 'Visualizar perfis alimentares',
                'seletividade.gerenciar'=> 'Registrar e remover alimentos',
            ],
            'Adaptações para Prova' => [
                'adaptacoes.ver' => 'Visualizar adaptações para prova',
            ],
            'Relatórios & Configuração' => [
                'relatorios.exportar' => 'Exportar relatórios gerais',
                'escola.configurar'   => 'Configurar escola',
            ],
        ];
    }

    /** Garante que os perfis do sistema existam na tabela school_roles para esta escola */
    private function ensureSystemRoles(int $schoolId): void
    {
        $systemRoles = [
            ['slug' => 'admin',     'name' => 'Administrador', 'spatie_role' => 'admin',     'color' => '#004B8D'],
            ['slug' => 'professor', 'name' => 'Professor',     'spatie_role' => 'professor', 'color' => '#059669'],
        ];

        foreach ($systemRoles as $sr) {
            // withTrashed() impede recriar perfis que foram removidos intencionalmente
            if (SchoolRole::withTrashed()->where('school_id', $schoolId)->where('slug', $sr['slug'])->exists()) {
                continue;
            }

            $spatieRole  = Role::where('name', $sr['spatie_role'])->first();
            $permissions = $spatieRole ? $spatieRole->permissions->pluck('name')->toArray() : [];

            SchoolRole::create([
                'school_id'   => $schoolId,
                'name'        => $sr['name'],
                'slug'        => $sr['slug'],
                'spatie_role' => $sr['spatie_role'],
                'color'       => $sr['color'],
                'permissions' => $permissions,
                'is_system'   => true,
            ]);
        }
    }

    public function index()
    {
        $schoolId = session('school_id');
        $this->ensureSystemRoles($schoolId);

        $roles  = SchoolRole::where('school_id', $schoolId)->orderBy('is_system', 'desc')->orderBy('name')->get();
        $groups = self::permissionGroups();

        return view('secretaria.config.perfis.index', compact('roles', 'groups'));
    }

    public function create()
    {
        $groups = self::permissionGroups();
        return view('secretaria.config.perfis.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $schoolId = session('school_id');

        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'color'       => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
        ]);

        $slug       = Str::slug($data['name'], '_');
        $spatieRole = "s{$schoolId}_{$slug}";

        // Garante slug único
        $suffix = 1;
        $base   = $slug;
        while (SchoolRole::where('school_id', $schoolId)->where('slug', $slug)->exists()) {
            $slug       = "{$base}_{$suffix}";
            $spatieRole = "s{$schoolId}_{$slug}";
            $suffix++;
        }

        // Cria o role no Spatie
        $role = Role::firstOrCreate(['name' => $spatieRole, 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        // Salva o school_role
        SchoolRole::create([
            'school_id'   => $schoolId,
            'name'        => $data['name'],
            'slug'        => $slug,
            'spatie_role' => $spatieRole,
            'color'       => $data['color'] ?? '#6B7280',
            'permissions' => $data['permissions'] ?? [],
            'is_system'   => false,
        ]);

        return redirect()->route('secretaria.config.perfis.index')
            ->with('success', 'Perfil criado com sucesso.');
    }

    public function edit(SchoolRole $perfil)
    {
        // Sincroniza permissões do Spatie → SchoolRole se o campo estiver vazio (migração de dados)
        if (empty($perfil->permissions)) {
            $spatieRole = Role::where('name', $perfil->spatie_role)->first();
            if ($spatieRole) {
                $perfil->update(['permissions' => $spatieRole->permissions->pluck('name')->toArray()]);
            }
        }

        $groups = self::permissionGroups();
        return view('secretaria.config.perfis.edit', compact('perfil', 'groups'));
    }

    public function update(Request $request, SchoolRole $perfil)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'color'       => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
        ]);

        // Admin sempre recebe todas as permissões, sem exceção
        if ($perfil->slug === 'admin') {
            $allPerms = collect(self::permissionGroups())->flatMap(fn($p) => array_keys($p))->toArray();
            $data['permissions'] = $allPerms;
        }

        // Atualiza permissões no Spatie
        $role = Role::where('name', $perfil->spatie_role)->first();
        if ($role) {
            $role->syncPermissions($data['permissions'] ?? []);
        }

        $perfil->update([
            'name'        => $data['name'],
            'color'       => $data['color'] ?? $perfil->color,
            'permissions' => $data['permissions'] ?? [],
        ]);

        return redirect()->route('secretaria.config.perfis.index')
            ->with('success', 'Perfil atualizado.');
    }

    public function destroy(SchoolRole $perfil)
    {
        if (in_array($perfil->slug, ['admin', 'professor'])) {
            return back()->withErrors(['Os perfis Administrador e Professor não podem ser removidos.']);
        }

        if ($perfil->usersCount() > 0) {
            return back()->withErrors(['Este perfil possui usuários vinculados. Reatribua-os antes de remover.']);
        }

        // Remove o role Spatie (apenas perfis customizados têm role próprio)
        if (! $perfil->is_system) {
            Role::where('name', $perfil->spatie_role)->delete();
        }

        $perfil->delete();

        return redirect()->route('secretaria.config.perfis.index')
            ->with('success', 'Perfil removido.');
    }
}
