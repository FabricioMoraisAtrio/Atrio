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
                'objetivos'          => $request->input('objetivos'),
                'adaptacoes'         => $request->input('adaptacoes'),
                'avaliacao'          => $request->input('avaliacao'),
                'progresso'          => $request->input('progresso'),
                'observacoes_livres' => $request->input('observacoes_livres'),
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