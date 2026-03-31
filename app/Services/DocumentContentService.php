<?php

namespace App\Services;

use Illuminate\Http\Request;

class DocumentContentService
{
    public static function buildContent(string $type, Request $request): array
    {
        return match($type) {
            'estudo_caso' => [
                'historico'          => $request->input('historico'),
                'barreiras'          => $request->input('barreiras'),
                'potencialidades'    => $request->input('potencialidades'),
                'observacoes_livres' => $request->input('observacoes_livres'),
            ],
            'pei' => [
                'materia'                 => $request->input('materia'),
                // Nível atual de desenvolvimento
                'nivel_academico'         => $request->input('nivel_academico'),
                'nivel_comunicacao'       => $request->input('nivel_comunicacao'),
                'nivel_socioemocional'    => $request->input('nivel_socioemocional'),
                // Metas estruturadas
                'metas_curto_prazo'       => $request->input('metas_curto_prazo'),
                'metas_medio_prazo'       => $request->input('metas_medio_prazo'),
                'metas_longo_prazo'       => $request->input('metas_longo_prazo'),
                // Estratégias e suporte especializado
                'estrategias_pedagogicas' => $request->input('estrategias_pedagogicas'),
                'aee_frequencia'          => $request->input('aee_frequencia'),
                'caa_recursos'            => $request->input('caa_recursos'),
                // Adaptações
                'objetivos'               => $request->input('objetivos'),
                'adaptacoes'              => json_decode($request->input('adaptacoes', '[]'), true) ?: [],
                'avaliacao'               => $request->input('avaliacao'),
                'observacoes_livres'      => $request->input('observacoes_livres'),
            ],
            'paee' => [
                'cronograma'         => $request->input('cronograma'),
                'recursos'           => $request->input('recursos'),
                'acessibilidade'     => $request->input('acessibilidade'),
                'parcerias'          => $request->input('parcerias'),
                'observacoes_livres' => $request->input('observacoes_livres'),
            ],
            default => [],
        };
    }
}