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
                'nivel_desenvolvimento'     => $request->input('nivel_desenvolvimento'),
                'comportamento_sala'        => $request->input('comportamento_sala'),
                'interacao_colegas'         => $request->input('interacao_colegas'),

                // Barreiras Identificadas
                'desafios_conteudo'         => $request->input('desafios_conteudo'),
                'barreiras_fisicas'         => $request->input('barreiras_fisicas'),

                // Potencialidades
                'interesses_motivacao'      => $request->input('interesses_motivacao'),
                'habilidades_destaque'      => $request->input('habilidades_destaque'),

                // Encaminhamentos
                'estrategias_sala'          => $request->input('estrategias_sala'),
                'adaptacoes_necessarias'    => $request->input('adaptacoes_necessarias'),
                'encaminhamentos_rede'      => $request->input('encaminhamentos_rede'),

                // Estratégias e Avaliação (professor regente)
                'estrategias_pedagogicas'   => $request->input('estrategias_pedagogicas'),
                'criterios_avaliacao'       => $request->input('criterios_avaliacao'),

                // Diagnóstico Pedagógico → alimenta o PEI
                'diagnostico_pedagogico'    => $request->input('diagnostico_pedagogico'),

                // Objetivos de Aprendizagem
                'objetivos_curto_prazo'     => $request->input('objetivos_curto_prazo'),
                'objetivos_medio_prazo'     => $request->input('objetivos_medio_prazo'),
                'objetivos_longo_prazo'     => $request->input('objetivos_longo_prazo'),

                // Equipe responsável → alimenta o PEI
                'equipe_titular'            => $request->input('equipe_titular'),
                'equipe_soe'                => $request->input('equipe_soe'),
                'equipe_scp'                => $request->input('equipe_scp'),
                'equipe_saee'               => $request->input('equipe_saee'),
                'equipe_psicologo'          => $request->input('equipe_psicologo'),
                'equipe_psicopedagogo'      => $request->input('equipe_psicopedagogo'),
                'equipe_fisioterapeuta'     => $request->input('equipe_fisioterapeuta'),
                'equipe_at'                 => $request->input('equipe_at'),

                'elaborado_por'             => \Illuminate\Support\Facades\Auth::user()?->name,
                'data_elaboracao'           => date('Y-m-d'),

                'observacoes_livres'        => $request->input('observacoes_livres'),
            ],

            'paee' => [
                // Diagnóstico / Perfil
                'diagnostico_perfil'       => $request->input('diagnostico_perfil'),

                // Objetivos do AEE
                'objetivo_geral'           => $request->input('objetivo_geral'),
                'objetivos_especificos'    => $request->input('objetivos_especificos'),

                // Recursos e Estratégias
                'tecnologias_assistivas'   => $request->input('tecnologias_assistivas'),
                'adaptacoes'               => $request->input('adaptacoes'),
                'metodologias'             => $request->input('metodologias'),

                // Organização do Atendimento
                'frequencia'               => $request->input('frequencia'),
                'duracao'                  => $request->input('duracao'),
                'local_atendimento'        => $request->input('local_atendimento'),

                // Avaliação e Monitoramento
                'criterios_avaliacao'      => $request->input('criterios_avaliacao'),
                'periodicidade_avaliacao'  => $request->input('periodicidade_avaliacao'),

                // Equipe
                'profissional_aee'         => $request->input('profissional_aee'),
                'equipe_colaborativa'      => $request->input('equipe_colaborativa'),

                'observacoes_livres'       => $request->input('observacoes_livres'),
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

        // Avaliações por meta: [meta_id => ['flag' => 'autonomia', 'obs' => '...']]
        $metas = [];
        foreach ($request->input('metas', []) as $metaId => $dados) {
            $metas[(int) $metaId] = [
                'flag' => $dados['flag'] ?? null,
                'obs'  => trim($dados['obs'] ?? ''),
            ];
        }

        $subjects[$slug] = [
            'subject_name'       => $request->input('subject_name'),
            'teacher_id'         => auth()->id(),
            'teacher_name'       => auth()->user()->name,
            'updated_at'         => now()->toDateTimeString(),
            'metas'              => $metas,
            'observacoes_livres' => $request->input('observacoes_livres'),
        ];

        return ['subjects' => $subjects, 'global' => $existing['global'] ?? []];
    }
}
