<?php

namespace App\Services;

use Illuminate\Http\Request;

class DocumentContentService
{
    public static function buildContent(string $type, Request $request): array
    {
        return match($type) {

            'estudo_caso' => [
                // Equipe do colégio
                'professor_titular'         => $request->input('professor_titular'),
                'soe'                       => $request->input('soe'),
                'scp'                       => $request->input('scp'),
                'saee'                      => $request->input('saee'),

                // Equipe multidisciplinar
                'psicologa'                 => $request->input('psicologa'),
                'psiquiatra'                => $request->input('psiquiatra'),
                'psicopedagoga'             => $request->input('psicopedagoga'),
                'fisioterapeuta'            => $request->input('fisioterapeuta'),
                'at'                        => $request->input('at'),

                // Trajetória escolar
                'historico'                 => $request->input('historico'),

                // A) Aspectos Cognitivos e Acadêmicos
                'atencao'                   => $request->input('atencao'),
                'memoria'                   => $request->input('memoria'),
                'raciocinio_logico'         => $request->input('raciocinio_logico'),
                'leitura_escrita'           => $request->input('leitura_escrita'),
                'matematica'                => $request->input('matematica'),
                'organizacao'               => $request->input('organizacao'),

                // B) Aspectos Comunicacionais
                'linguagem_expressiva'      => $request->input('linguagem_expressiva'),
                'linguagem_receptiva'       => $request->input('linguagem_receptiva'),
                'comunicacao_alternativa'   => $request->input('comunicacao_alternativa'),

                // C) Aspectos Socioemocionais e Comportamentais
                'interacao_social'          => $request->input('interacao_social'),
                'autonomia'                 => $request->input('autonomia'),
                'regulacao_emocional'       => $request->input('regulacao_emocional'),
                'interesses_motivacao'      => $request->input('interesses_motivacao'),

                // D) Aspectos Sensoriais e Motores
                'motricidade_grossa'        => $request->input('motricidade_grossa'),
                'motricidade_fina'          => $request->input('motricidade_fina'),
                'processamento_sensorial'   => $request->input('processamento_sensorial'),

                // Análise da Participação e Rotina Escolar
                'rotina'                    => $request->input('rotina'),
                'participacao_aulas'        => $request->input('participacao_aulas'),
                'participacao_intervalos'   => $request->input('participacao_intervalos'),
                'apoios_necessarios'        => $request->input('apoios_necessarios'),

                // Síntese
                'potencialidades'           => $request->input('potencialidades'),
                'barreiras'                 => $request->input('barreiras'),
                'encaminhamentos'           => $request->input('encaminhamentos'),

                // Responsáveis pela elaboração
                'profissional_aee'          => $request->input('profissional_aee'),
                'orientador_educacional'    => $request->input('orientador_educacional'),
                'contribuicoes'             => $request->input('contribuicoes'),

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

                // Adaptações curriculares (tags JSON)
                'adaptacoes'                 => json_decode($request->input('adaptacoes', '[]'), true) ?: [],

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

            'adequacao_curricular' => [
                'componente'         => $request->input('componente'),
                'objetivo'           => $request->input('objetivo'),
                'estrategias'        => $request->input('estrategias'),
                'avaliacao'          => $request->input('avaliacao'),
                'observacoes_livres' => $request->input('observacoes_livres'),
            ],

            'material_apoio' => [
                'descricao'          => $request->input('descricao'),
                'recursos'           => $request->input('recursos'),
                'responsavel'        => $request->input('responsavel'),
                'observacoes_livres' => $request->input('observacoes_livres'),
            ],

            default => [],
        };
    }
}