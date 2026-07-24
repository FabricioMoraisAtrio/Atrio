<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Models\SubjectInventoryItem;
use App\Services\DocumentContentService;
use App\Services\StudentTimelineService;
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

        // Linha do tempo resumida (roadmap de evolução) — 6 eventos mais recentes
        $timeline = app(StudentTimelineService::class)->build($aluno, 6);

        return view('secretaria.pei.consolidado', compact(
            'aluno', 'peiConsolidado', 'peiGlobal', 'peiSubjects', 'inventoryItems', 'ec', 'timeline'
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
     * Retorna: ['academica' => [...], 'socioemocional' => [...], 'funcional' => [...], 'global' => [...]]
     * Cada item: ['meta', 'flag', 'obs', 'subject_name', 'teacher_name']
     *
     * - Metas acadêmicas (disciplinas): avaliadas com flag/obs. No novo formato o texto
     *   da meta vem embutido em $dados['texto']; no formato legado é resolvido via
     *   SubjectInventoryItem, respeitando a categoria antiga (academica/socioemocional/global).
     * - Metas socioemocionais e funcionais (regente): texto livre, sem flag.
     */
    public static function consolidarInventario(array $peiSubjects, \Illuminate\Support\Collection $inventoryItems, array $peiGlobal = []): array
    {
        $resultado = ['academica' => [], 'socioemocional' => [], 'funcional' => [], 'global' => []];

        // Metas socioemocionais/funcionais preenchidas pelos perfis com acesso (PEI global)
        foreach (['socioemocional' => 'metas_socioemocionais', 'funcional' => 'metas_funcionais'] as $cat => $campo) {
            if (! empty($peiGlobal[$campo])) {
                $resultado[$cat][] = [
                    'meta'         => $peiGlobal[$campo],
                    'flag'         => null,
                    'obs'          => '',
                    'subject_name' => 'Equipe / Coordenação',
                    'teacher_name' => '',
                ];
            }
        }

        foreach ($peiSubjects as $slug => $secao) {
            $subjectName = $secao['subject_name'] ?? $slug;
            $teacherName = $secao['teacher_name'] ?? '';

            foreach ($secao['metas'] ?? [] as $metaId => $dados) {
                $texto = trim($dados['texto'] ?? '');
                $cat   = $dados['cat'] ?? '';

                if ($cat === '') {
                    // Formato legado: sem categoria salva — resolve via inventário (categoria
                    // antiga) ou assume acadêmica.
                    $item = $inventoryItems->get((int) $metaId);
                    $cat  = $item->categoria ?? 'academica';
                    if ($texto === '' && $item) {
                        $texto = $item->meta;
                    }
                }

                if ($texto === '') continue;
                if (! array_key_exists($cat, $resultado)) continue;

                $resultado[$cat][] = [
                    'meta'         => $texto,
                    'flag'         => $dados['flag'] ?? null,
                    'obs'          => $dados['obs'] ?? '',
                    'subject_name' => $subjectName,
                    'teacher_name' => $teacherName,
                ];
            }

            // Compatibilidade: texto livre socio/funcional preenchido no formato anterior
            foreach (['socioemocional' => 'metas_socioemocionais', 'funcional' => 'metas_funcionais'] as $cat => $campo) {
                if (! empty($secao[$campo])) {
                    $resultado[$cat][] = [
                        'meta'         => $secao[$campo],
                        'flag'         => null,
                        'obs'          => '',
                        'subject_name' => $subjectName,
                        'teacher_name' => $teacherName,
                    ];
                }
            }
        }

        return $resultado;
    }
}
