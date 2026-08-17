<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Services\DocumentContentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /** Rótulo amigável do tipo de documento para mensagens ao usuário. */
    private static function tipoLabel(string $type): string
    {
        return [
            'estudo_caso'      => 'Estudo de Caso',
            'paee'             => 'PAEE',
            'pei'              => 'PEI',
            'pei_consolidado'  => 'PEI',
        ][$type] ?? ucfirst(str_replace('_', ' ', $type));
    }

    /**
     * Campos mínimos obrigatórios por tipo de documento (regra pedagógica):
     * Estudo de Caso exige o histórico escolar; PAEE exige ao menos 1 item de Diagnóstico/Perfil.
     * @return array{rules: array, messages: array}
     */
    private static function obrigatorios(string $type): array
    {
        return \App\Support\CamposDocumento::regras(session('school_id'), $type);
    }

    public function create(Student $aluno, Request $request)
    {
        $type = $request->query('type', 'estudo_caso');

        if ($type === 'paee') {
            $hasCase = Document::where('student_id', $aluno->id)
                ->where('type', 'estudo_caso')
                ->where('year', date('Y'))
                ->exists();

            if (! $hasCase) {
                return redirect()->route('secretaria.alunos.show', $aluno)
                    ->withErrors(['documento' => 'É necessário preencher o Estudo de Caso antes de criar o PAEE.']);
            }
        }

        $exists = Document::where('student_id', $aluno->id)
            ->where('type', $type)
            ->where('year', date('Y'))
            ->exists();

        if ($exists) {
            return back()->withErrors(['documento' => 'Já existe um ' . self::tipoLabel($type) . ' para este estudante em ' . date('Y') . '.']);
        }

        $estudo_caso_content = [];
        if ($type === 'paee') {
            $estudo_caso_content = Document::where('student_id', $aluno->id)
                ->where('type', 'estudo_caso')
                ->where('year', date('Y'))
                ->value('content') ?? [];
        }

        return view('secretaria.documentos.create', compact('aluno', 'type', 'estudo_caso_content'));
    }

    public function store(Student $aluno, Request $request)
    {
        $type  = $request->input('type');
        $obrig = self::obrigatorios($type);

        $request->validate(
            ['type' => 'required|in:estudo_caso,paee', 'nivel_bloom' => 'nullable|in:' . implode(',', array_keys(Student::NIVEIS_BLOOM))] + $obrig['rules'],
            $obrig['messages'],
        );

        if ($type === 'paee') {
            $hasCase = Document::where('student_id', $aluno->id)
                ->where('type', 'estudo_caso')
                ->where('year', date('Y'))
                ->exists();

            if (! $hasCase) {
                return back()->withErrors(['documento' => 'Preencha o Estudo de Caso antes.']);
            }
        }

        $content = DocumentContentService::buildContent($type, $request);

        Document::create([
            'school_id'  => session('school_id'),
            'student_id' => $aluno->id,
            'author_id'  => auth()->id(),
            'type'       => $type,
            'year'       => date('Y'),
            'status'     => 'published',
            'content'    => $content,
        ]);

        if ($type === 'estudo_caso') {
            $aluno->update([
                'has_case_study' => true,
                'nivel_bloom'    => $request->input('nivel_bloom'),
            ]);

            // Cria o PEI vazio automaticamente para os professores preencherem
            Document::updateOrCreate(
                [
                    'school_id'  => session('school_id'),
                    'student_id' => $aluno->id,
                    'type'       => 'pei',
                    'year'       => date('Y'),
                ],
                [
                    'author_id' => auth()->id(),
                    'status'    => 'published',
                    'content'   => DocumentContentService::emptyPei(),
                ]
            );
        }

        $listRoute = $type === 'paee'
            ? 'secretaria.rotinas.documentos.paee'
            : 'secretaria.rotinas.documentos.estudo-caso';

        return redirect()->route($listRoute)
            ->with('success', self::tipoLabel($type) . ' criado com sucesso.');
    }

    public function show(Document $documento)
    {
        // PEIs individuais dos professores são registros privados — não acessíveis a outros usuários
        if ($documento->type === 'pei' && $documento->author_id !== auth()->id()) {
            abort(403, 'Os PEIs individuais dos professores são registros privados.');
        }

        $documento->load('student', 'author');
        return view('secretaria.documentos.show', compact('documento'));
    }

    public function edit(Document $documento)
    {
        if ((int) $documento->year !== (int) date('Y')) {
            return redirect()->route('secretaria.alunos.show', $documento->student_id)
                ->with('error', 'Documentos de anos anteriores são somente leitura.');
        }
        // PEIs individuais dos professores são registros privados
        if ($documento->type === 'pei' && $documento->author_id !== auth()->id()) {
            abort(403, 'Os PEIs individuais dos professores são registros privados.');
        }

        $estudo_caso_content = [];
        if (in_array($documento->type, ['paee', 'pei'])) {
            $estudo_caso_content = Document::where('student_id', $documento->student_id)
                ->where('type', 'estudo_caso')
                ->where('year', date('Y'))
                ->value('content') ?? [];
        }

        $documento->load('student');
        return view('secretaria.documentos.edit', compact('documento', 'estudo_caso_content'));
    }

    public function update(Request $request, Document $documento)
    {
        if ((int) $documento->year !== (int) date('Y')) {
            return redirect()->route('secretaria.alunos.show', $documento->student_id)
                ->with('error', 'Documentos de anos anteriores são somente leitura.');
        }
        $obrig = self::obrigatorios($documento->type);
        if ($obrig['rules']) {
            $request->validate($obrig['rules'], $obrig['messages']);
        }

        if ($documento->type === 'estudo_caso') {
            $request->validate(['nivel_bloom' => 'nullable|in:' . implode(',', array_keys(Student::NIVEIS_BLOOM))]);
            $documento->student->update(['nivel_bloom' => $request->input('nivel_bloom')]);
        }

        if ($documento->type === 'pei') {
            $global = DocumentContentService::buildContent('pei', $request);
            $content = array_merge($documento->content ?? [], ['global' => $global]);
        } else {
            $content = DocumentContentService::buildContent($documento->type, $request);
        }

        $documento->update([
            'content' => $content,
        ]);

        return redirect()->route('secretaria.documentos.show', $documento)
            ->with('success', 'Documento atualizado com sucesso.');
    }

    public function destroy(Document $documento)
    {
        $student = $documento->student;
        $type    = $documento->type;

        $documento->delete();

        if ($type === 'estudo_caso') {
            $student->update(['has_case_study' => false]);

            // Remove o PEI do estudante junto com o Estudo de Caso
            Document::where('student_id', $student->id)
                ->where('type', 'pei')
                ->where('year', date('Y'))
                ->get()
                ->each(fn($pei) => $pei->delete());
        }

        $listRoute = match ($type) {
            'paee' => 'secretaria.rotinas.documentos.paee',
            'pei'  => 'secretaria.rotinas.documentos.pei',
            default => 'secretaria.rotinas.documentos.estudo-caso',
        };

        return redirect()->route($listRoute)
            ->with('success', 'Documento removido.');
    }
}
