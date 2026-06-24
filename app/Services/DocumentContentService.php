<?php

namespace App\Services;

use Illuminate\Http\Request;

class DocumentContentService
{
    public static function buildContent(string $type, Request $request): array
    {
        return match($type) {

            'estudo_caso' => [
                // Identificação complementar
                'contexto_familiar'         => $request->input('contexto_familiar'),

                // Histórico Escolar
                'historico_escolar'         => $request->input('historico_escolar'),
                'frequencia_assiduidade'    => $request->input('frequencia_assiduidade'),

                // Observações Pedagógicas
                'obs_pedagogicas'           => $request->input('obs_pedagogicas', []),
                'obs_pedagogicas_obs'       => $request->input('obs_pedagogicas_obs'),

                // Barreiras Identificadas
                'barreiras'                 => $request->input('barreiras', []),
                'barreiras_obs'             => $request->input('barreiras_obs'),

                // Potencialidades
                'potencialidades'           => $request->input('potencialidades', []),
                'potencialidades_obs'       => $request->input('potencialidades_obs'),

                // Encaminhamentos
                'encaminhamentos'           => $request->input('encaminhamentos', []),
                'encaminhamentos_obs'       => $request->input('encaminhamentos_obs'),

                // Equipe responsável
                'equipe_participantes'      => array_values(array_filter(
                    $request->input('equipe_participantes', []),
                    fn($p) => !empty($p['nome'])
                )),

                'elaborado_por'             => \Illuminate\Support\Facades\Auth::user()?->name,
                'data_elaboracao'           => date('Y-m-d'),

                'observacoes_livres'        => $request->input('observacoes_livres'),
            ],

            'paee' => [
                'diagnostico_perfil'          => $request->input('diagnostico_perfil', []),
                'diagnostico_perfil_obs'      => $request->input('diagnostico_perfil_obs'),

                'recursos_estrategias'        => $request->input('recursos_estrategias', []),
                'recursos_estrategias_obs'    => $request->input('recursos_estrategias_obs'),

                'organizacao_atendimento'     => $request->input('organizacao_atendimento', []),
                'organizacao_atendimento_obs' => $request->input('organizacao_atendimento_obs'),

                'avaliacao_monitoramento'     => $request->input('avaliacao_monitoramento', []),
                'avaliacao_monitoramento_obs' => $request->input('avaliacao_monitoramento_obs'),

                'equipe_participantes'        => array_values(array_filter(
                    $request->input('equipe_participantes', []),
                    fn($p) => !empty($p['nome'])
                )),

                'observacoes_livres'          => $request->input('observacoes_livres'),
            ],

            'pei' => [
                'objetivos_curto_prazo'   => $request->input('objetivos_curto_prazo'),
                'objetivos_medio_prazo'   => $request->input('objetivos_medio_prazo'),
                'objetivos_longo_prazo'   => $request->input('objetivos_longo_prazo'),
                'estrategias_pedagogicas' => $request->input('estrategias_pedagogicas'),
                'criterios_avaliacao'     => $request->input('criterios_avaliacao'),
            ],

            'pei_consolidado' => [
                'observacoes' => $request->input('observacoes'),
            ],

            default => [],
        };
    }

    /**
     * Retorna um PEI vazio estruturado por matérias da turma do aluno.
     * subjects é um array indexado por subject slug.
     */
    public static function emptyPei(): array
    {
        return ['subjects' => []];
    }

    /**
     * Mescla a contribuição de um professor no conteúdo existente do PEI.
     * Atualiza apenas a entrada da matéria do professor, preservando o restante.
     */
    /**
     * Mescla a contribuição de um professor no PEI.
     * Salva as avaliações por meta_id + texto livre.
     */
    public static function mergePeiSubject(array $existing, Request $request): array
    {
        $subjects = $existing['subjects'] ?? [];
        $slug     = $request->input('subject_slug');

        // Avaliações por meta: [meta_id => ['texto'=>'...', 'cat'=>'academica', 'flag'=>'autonomia', 'obs'=>'...']]
        // Texto e categoria são salvos junto para o conteúdo ser auto-contido (independe de lookup posterior).
        $metas = [];
        foreach ($request->input('metas', []) as $metaId => $dados) {
            $metas[(int) $metaId] = [
                'texto' => trim($dados['texto'] ?? ''),
                'cat'   => $dados['cat'] ?? 'academica',
                'flag'  => $dados['flag'] ?? null,
                'obs'   => trim($dados['obs'] ?? ''),
            ];
        }

        $subjects[$slug] = [
            'subject_name'       => $request->input('subject_name'),
            'subject_tipo'       => $request->input('subject_tipo'),
            'teacher_id'         => auth()->id(),
            'teacher_name'       => auth()->user()->name,
            'updated_at'         => now()->toDateTimeString(),
            'metas'              => $metas,
            'observacoes_livres' => $request->input('observacoes_livres'),
        ];

        return ['subjects' => $subjects, 'global' => $existing['global'] ?? []];
    }
}
