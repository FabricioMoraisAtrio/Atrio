<?php

namespace App\Services;

use App\Models\SchoolSetting;
use Illuminate\Support\Carbon;

/**
 * Datas dos bimestres (configuradas por escola) e regras de encerramento.
 * As datas ficam em SchoolSetting nas chaves bim{N}_inicio / bim{N}_fim (Y-m-d).
 */
class BimestreService
{
    public const BIMESTRES = [1, 2, 3, 4];

    /** @return array<int, array{inicio:?Carbon, fim:?Carbon}> */
    public function datas(int $schoolId): array
    {
        $out = [];
        foreach (self::BIMESTRES as $b) {
            $ini = SchoolSetting::getValue($schoolId, "bim{$b}_inicio");
            $fim = SchoolSetting::getValue($schoolId, "bim{$b}_fim");
            $out[$b] = [
                'inicio' => $ini !== '' ? Carbon::parse($ini) : null,
                'fim'    => $fim !== '' ? Carbon::parse($fim) : null,
            ];
        }
        return $out;
    }

    /** Pode encerrar hoje? Sim se não há data de início configurada ou se já começou. */
    public function podeEncerrar(array $datas, int $bimestre): bool
    {
        $ini = $datas[$bimestre]['inicio'] ?? null;
        return ! $ini || Carbon::now()->startOfDay()->gte($ini->copy()->startOfDay());
    }

    /** Rótulo de situação do bimestre (independe do fechamento). */
    public function situacao(array $datas, int $bimestre): string
    {
        $ini = $datas[$bimestre]['inicio'] ?? null;
        $fim = $datas[$bimestre]['fim'] ?? null;
        $hoje = Carbon::now()->startOfDay();

        if ($ini && $hoje->lt($ini->copy()->startOfDay())) return 'a_iniciar';
        if ($fim && $hoje->gt($fim->copy()->startOfDay())) return 'prazo_encerrado';
        return 'em_andamento';
    }
}
