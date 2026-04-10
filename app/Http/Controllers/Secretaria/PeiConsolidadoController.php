<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Models\SubjectInventoryItem;
use App\Services\DocumentContentService;
use Illuminate\Http\Request;

class PeiConsolidadoController extends Controller
{
    /**
     * Exibe o PEI Consolidado — lê dados do EC e do PEI compartilhado.
     */
    public function edit(Student $aluno)
    {
        $aluno->load(['schoolClasses' => fn($q) => $q->where('year', date('Y')), 'school']);

        $peiConsolidado = Document::where('student_id', $aluno->id)
            ->where('year', date('Y'))
            ->where('type', 'pei_consolidado')
            ->first();

        // Documento PEI compartilhado (único por aluno/ano)
        $peiDoc      = Document::where('student_id', $aluno->id)
            ->where('year', date('Y'))
            ->where('type', 'pei')
            ->first();

        $peiContent  = $peiDoc?->content ?? [];
        $peiGlobal   = $peiContent['global'] ?? [];
        $peiSubjects = $peiContent['subjects'] ?? [];

        // Carrega itens do inventário referenciados no PEI
        $inventoryItems = self::loadInventoryItems($peiSubjects);

        // Conteúdo do Estudo de Caso
        $ec = Document::where('student_id', $aluno->id)
            ->where('year', date('Y'))
            ->where('type', 'estudo_caso')
            ->value('content') ?? [];

        return view('secretaria.pei.consolidado', compact(
            'aluno', 'peiConsolidado', 'peiGlobal', 'peiSubjects', 'inventoryItems', 'ec'
        ));
    }

    /**
     * Salva apenas as "Observações" adicionais do PEI Consolidado.
     */
    public function update(Request $request, Student $aluno)
    {
        $content = DocumentContentService::buildContent('pei_consolidado', $request);

        Document::updateOrCreate(
            [
                'student_id' => $aluno->id,
                'year'       => date('Y'),
                'type'       => 'pei_consolidado',
                'school_id'  => session('school_id'),
            ],
            [
                'author_id' => auth()->id(),
                'status'    => 'published',
                'content'   => $content,
            ]
        );

        return redirect()->route('secretaria.alunos.pei-consolidado', $aluno)
            ->with('success', 'Observações salvas com sucesso.');
    }

    /**
     * Carrega SubjectInventoryItems referenciados nos subjects do PEI.
     */
    public static function loadInventoryItems(array $peiSubjects): \Illuminate\Support\Collection
    {
        $ids = collect($peiSubjects)
            ->flatMap(fn($s) => array_keys($s['metas'] ?? []))
            ->map(fn($id) => (int) $id)
            ->unique()->values()->all();

        return $ids
            ? SubjectInventoryItem::withoutGlobalScopes()->whereIn('id', $ids)->get()->keyBy('id')
            : collect();
    }

    /**
     * Agrega metas de todos os subjects do PEI por categoria.
     * Retorna: ['academica' => [...], 'socioemocional' => [...], 'global' => [...]]
     * Cada item: ['meta', 'flag', 'obs', 'subject_name', 'teacher_name']
     */
    public static function consolidarInventario(array $peiSubjects, \Illuminate\Support\Collection $inventoryItems): array
    {
        $resultado = ['academica' => [], 'socioemocional' => [], 'global' => []];

        foreach ($peiSubjects as $slug => $secao) {
            foreach ($secao['metas'] ?? [] as $metaId => $dados) {
                $item = $inventoryItems->get((int) $metaId);
                if (! $item) continue;

                $cat = $item->categoria; // 'academica' | 'socioemocional' | 'global'
                if (! array_key_exists($cat, $resultado)) continue;

                $resultado[$cat][] = [
                    'meta'         => $item->meta,
                    'flag'         => $dados['flag'] ?? null,
                    'obs'          => $dados['obs'] ?? '',
                    'subject_name' => $secao['subject_name'] ?? $slug,
                    'teacher_name' => $secao['teacher_name'] ?? '',
                ];
            }
        }

        return $resultado;
    }
}
