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

                // Equipe responsável
                'elaborado_por'             => $request->input('elaborado_por'),
                'data_elaboracao'           => date('Y-m-d'),

                'observacoes_livres'        => $request->input('observacoes_livres'),
            ],

            'pei' => [
                // Identificação da disciplina
                'materia'                    => $request->input('materia'),

                // Estratégias do professor
                'estrategias_pedagogicas'    => $request->input('estrategias_pedagogicas'),

                // Inventário por categoria
                'habilidades_academicas'     => $request->input('habilidades_academicas', []),
                'habilidades_socioemocionais' => $request->input('habilidades_socioemocionais', []),
                'habilidades_funcionais'     => $request->input('habilidades_funcionais', []),

                'observacoes_livres'         => $request->input('observacoes_livres'),
            ],

            'paee' => [
                'cronograma'         => $request->input('cronograma'),
                'recursos'           => $request->input('recursos'),
                'acessibilidade'     => $request->input('acessibilidade'),
                'parcerias'          => $request->input('parcerias'),
                'observacoes_livres' => $request->input('observacoes_livres'),
            ],

            'pei_consolidado' => [
                'necessidades_educacionais' => $request->input('necessidades_educacionais'),
                'adaptacoes_avaliacao'      => $request->input('adaptacoes_avaliacao'),
                'observacoes'               => $request->input('observacoes'),
            ],

            'atendimento' => [
                'data'               => $request->input('data'),
                'tipo'               => $request->input('tipo'),
                'profissional'       => $request->input('profissional'),
                'descricao'          => $request->input('descricao'),
                'observacoes_livres' => $request->input('observacoes_livres'),
            ],

            default => [],
        };
    }
}