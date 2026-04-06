<?php

if (! function_exists('term')) {
    /**
     * Retorna o termo customizado da escola ou o padrão do sistema.
     * Uso: term('aluno'), term('turmas'), term('laudo')
     */
    function term(string $key): string
    {
        static $defaults = [
            'aluno'        => 'Aluno',
            'alunos'       => 'Alunos',
            'turma'        => 'Turma',
            'turmas'       => 'Turmas',
            'laudo'        => 'Laudo',
            'laudos'       => 'Laudos',
            'professor'    => 'Professor',
            'professores'  => 'Professores',
            'coordenador'  => 'Coordenador',
            'orientador'   => 'Orientador',
            'documento'    => 'Documento',
            'documentos'   => 'Documentos',
            'observacao'   => 'Observação',
            'observacoes'  => 'Observações',
        ];

        static $cache = [];

        $schoolId = session('school_id');

        if (! $schoolId) {
            return $defaults[$key] ?? ucfirst($key);
        }

        if (! isset($cache[$schoolId])) {
            $cache[$schoolId] = \App\Models\SchoolSetting::getAllForSchool((int) $schoolId);
        }

        return $cache[$schoolId]["term_{$key}"] ?? ($defaults[$key] ?? ucfirst($key));
    }
}
