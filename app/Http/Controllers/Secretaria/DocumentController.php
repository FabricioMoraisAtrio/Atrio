<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Services\DocumentContentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
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
            return back()->withErrors(['documento' => 'Já existe um ' . strtoupper($type) . ' para este aluno em ' . date('Y') . '.']);
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
        $request->validate([
            'type' => 'required|in:estudo_caso,paee',
        ]);

        $type = $request->input('type');

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
            $aluno->update(['has_case_study' => true]);

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

        return redirect()->route('secretaria.alunos.show', $aluno)
            ->with('success', strtoupper($type) . ' criado com sucesso.');
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

            // Remove o PEI do aluno junto com o Estudo de Caso
            Document::where('student_id', $student->id)
                ->where('type', 'pei')
                ->where('year', date('Y'))
                ->delete();
        }

        return redirect()->route('secretaria.alunos.show', $student)
            ->with('success', 'Documento removido.');
    }
}
