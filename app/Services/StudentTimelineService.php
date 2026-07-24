<?php

namespace App\Services;

use App\Models\BimestreClosing;
use App\Models\Meeting;
use App\Models\Student;

/**
 * Monta a "linha do tempo" (roadmap de evolução) de um aluno, agregando
 * eventos de várias origens numa lista única ordenada por data (desc):
 *  - evolução de metas do PEI (avaliações por bimestre)
 *  - reuniões / atas
 *  - laudos anexados
 *  - observações do mural (críticas destacadas)
 */
class StudentTimelineService
{
    /**
     * @return array<int, array{data:\Illuminate\Support\Carbon, tipo:string, titulo:string, descricao:?string, status:?string, critico:bool}>
     */
    public function build(Student $aluno, ?int $limit = null): array
    {
        $eventos = [];

        // Evolução de metas (uma entrada por avaliação de bimestre)
        foreach ($aluno->academicGoals()->with('progressos')->get() as $meta) {
            foreach ($meta->progressos as $p) {
                $eventos[] = [
                    'data'      => $p->updated_at,
                    'tipo'      => 'meta',
                    'titulo'    => 'Meta avaliada — ' . $p->bimestre . 'º bimestre',
                    'descricao' => $meta->meta,
                    'status'    => $p->status,
                    'critico'   => false,
                ];
            }
        }

        // Reuniões / atas
        foreach ($aluno->meetings()->get() as $r) {
            $eventos[] = [
                'data'      => $r->data,
                'tipo'      => 'reuniao',
                'titulo'    => 'Reunião — ' . (Meeting::TIPOS[$r->tipo] ?? 'Reunião'),
                'descricao' => $r->participantes,
                'status'    => null,
                'critico'   => false,
            ];
        }

        // Laudos
        foreach ($aluno->laudos()->get() as $l) {
            $eventos[] = [
                'data'      => $l->data_laudo,
                'tipo'      => 'laudo',
                'titulo'    => 'Laudo ' . $l->tipo_label . ' anexado',
                'descricao' => $l->descricao,
                'status'    => null,
                'critico'   => false,
            ];
        }

        // Observações do mural
        foreach ($aluno->observations()->get() as $o) {
            $critico = $o->urgency === 'critico';
            $eventos[] = [
                'data'      => $o->created_at,
                'tipo'      => 'observacao',
                'titulo'    => $critico ? 'Observação crítica' : 'Observação',
                'descricao' => $o->content,
                'status'    => null,
                'critico'   => $critico,
            ];
        }

        // Fechamentos de bimestre
        foreach ($aluno->bimestreClosings()->get() as $c) {
            $pct = $c->snapshot['percentual'] ?? null;
            $eventos[] = [
                'data'      => $c->created_at,
                'tipo'      => 'fechamento',
                'titulo'    => 'Fechamento do ' . $c->bimestre . 'º bimestre',
                'descricao' => $pct !== null ? $pct . '% das metas atingidas' : 'Bimestre fechado',
                'status'    => null,
                'critico'   => false,
            ];
        }

        // Mais recentes primeiro; datas nulas vão para o fim.
        usort($eventos, function ($a, $b) {
            if (! $a['data']) return 1;
            if (! $b['data']) return -1;
            return $b['data'] <=> $a['data'];
        });

        return $limit ? array_slice($eventos, 0, $limit) : $eventos;
    }
}
