<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Services\DocumentContentService;
use Illuminate\Http\Request;

class PeiConsolidadoController extends Controller
{
    /**
     * Exibe o formulário de preenchimento/edição do PEI Consolidado.
     * Carrega todos os PEIs individuais dos professores para exibição no form.
     */
    public function edit(Student $aluno)
    {
        $aluno->load(['schoolClasses' => fn($q) => $q->where('year', date('Y')), 'school']);

        $peiConsolidado = Document::where('student_id', $aluno->id)
            ->where('year', date('Y'))
            ->where('type', 'pei_consolidado')
            ->first();

        // Todos os PEIs individuais dos professores
        $peis = Document::where('student_id', $aluno->id)
            ->where('year', date('Y'))
            ->where('type', 'pei')
            ->with('author:id,name')
            ->get();

        // Consolida os inventários de todos os professores por categoria
        $inventario = $this->consolidarInventario($peis);

        return view('secretaria.pei.consolidado', compact('aluno', 'peiConsolidado', 'peis', 'inventario'));
    }

    /**
     * Salva (cria ou atualiza) o PEI Consolidado.
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
                'status'    => $request->input('status', 'draft'),
                'content'   => $content,
            ]
        );

        return redirect()->route('secretaria.alunos.pei-consolidado', $aluno)
            ->with('success', 'PEI Consolidado salvo com sucesso.');
    }

    /**
     * Agrega habilidades de todos os PEIs individuais por categoria.
     */
    public static function consolidarInventario($peis): array
    {
        $categorias = ['habilidades_academicas', 'habilidades_socioemocionais', 'habilidades_funcionais'];
        $resultado  = array_fill_keys($categorias, []);

        foreach ($peis as $pei) {
            $content = $pei->content ?? [];
            foreach ($categorias as $cat) {
                if (!empty($content[$cat]) && is_array($content[$cat])) {
                    foreach ($content[$cat] as $item) {
                        if (!empty($item['meta'])) {
                            $resultado[$cat][] = array_merge($item, [
                                '_autor' => $pei->author->name ?? '—',
                            ]);
                        }
                    }
                }
            }
        }

        return $resultado;
    }
}
