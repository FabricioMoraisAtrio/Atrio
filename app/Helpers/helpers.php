<?php

if (! function_exists('term')) {
    /**
     * Retorna o termo customizado da escola ou o padrão do sistema.
     * Uso: term('aluno'), term('turmas'), term('laudo')
     */
    function term(string $key): string
    {
        static $defaults = [
            'aluno'           => 'Estudante',
            'alunos'          => 'Estudantes',
            'turma'           => 'Turma',
            'turmas'          => 'Turmas',
            'laudo'           => 'Laudo',
            'laudos'          => 'Laudos',
            'professor'       => 'Professor',
            'professores'     => 'Professores',
            'coordenador'     => 'Coordenador',
            'orientador'      => 'Orientador',
            'documento'       => 'Documento',
            'documentos'      => 'Documentos',
            'observacao'      => 'Observação',
            'observacoes'     => 'Observações',
            'publico_alvo'    => 'Público Alvo',
            'nao_publico_alvo'=> 'Não público alvo',
        ];

        static $cache = [];

        $schoolId = session('school_id');

        if (! $schoolId) {
            return $defaults[$key] ?? ucfirst($key);
        }

        if (! isset($cache[$schoolId])) {
            $cache[$schoolId] = \App\Models\SchoolSetting::getAllForSchool((int) $schoolId);
        }

        $val = $cache[$schoolId]["term_{$key}"] ?? '';
        return ($val !== '') ? $val : ($defaults[$key] ?? ucfirst($key));
    }
}

if (! function_exists('rotina_map')) {
    /** Mapa módulo → permissão de acesso à rotina (visibilidade no menu/dashboard). */
    function rotina_map(): array
    {
        return [
            'painel'         => 'rotina.painel',
            'turmas'         => 'rotina.turmas',
            'alunos'         => 'rotina.alunos',
            'documentos'     => 'rotina.documentos',
            'adaptacoes'     => 'rotina.adaptacoes',
            'reunioes'       => 'rotina.reunioes',
            'linha_do_tempo' => 'rotina.linha_do_tempo',
            'seletividade'   => 'rotina.seletividade',
            'usuarios'       => 'rotina.usuarios',
        ];
    }
}

if (! function_exists('rotina_filtrar')) {
    /**
     * Se o menu/dashboard do usuário atual deve ser filtrado pelas permissões rotina.*.
     * Vale para o professor e perfis customizados (s{escola}_*); papéis internos
     * admin/coordenador/orientador veem tudo. Só filtra depois que as permissões existem
     * (evita esconder tudo antes do migrate). Memoizado por request.
     */
    function rotina_filtrar(): bool
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $user = auth()->user();
        if (! $user) {
            return $cache = false;
        }

        $ehCustom = $user->roles->contains(fn ($r) => str_starts_with($r->name, 's' . session('school_id') . '_'));

        return $cache = ($user->hasRole('professor') || $ehCustom)
            && \Spatie\Permission\Models\Permission::where('name', 'rotina.painel')->exists();
    }
}

if (! function_exists('pode_rotina')) {
    /**
     * Se o usuário atual pode ver uma rotina no menu/dashboard.
     * Regra ÚNICA usada pela barra lateral e pelos dashboards iniciais.
     * $modulo = chave do módulo (ex.: 'painel'); $perm = permissão explícita (ex.: 'rotina.painel').
     */
    function pode_rotina(?string $modulo = null, ?string $perm = null): bool
    {
        if (! rotina_filtrar()) {
            return true;
        }

        $p = $perm ?: ($modulo ? (rotina_map()[$modulo] ?? null) : null);

        return ! $p || auth()->user()->can($p);
    }
}
