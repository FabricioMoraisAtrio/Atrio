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

        if (in_array($type, ['pei', 'paee'])) {
            $hasCase = Document::where('student_id', $aluno->id)
                ->where('type', 'estudo_caso')
                ->where('year', date('Y'))
                ->exists();

            if (! $hasCase) {
                return redirect()->route('secretaria.alunos.show', $aluno)
                    ->withErrors(['documento' => 'É necessário preencher o Estudo de Caso antes de criar PEI ou PAEE.']);
            }
        }

            $exists = Document::where('student_id', $aluno->id)
                ->where('type', $type)
                ->where('year', date('Y'))
                ->exists();

            if ($exists) {
                return back()->withErrors(['documento' => 'Já existe um ' . strtoupper($type) . ' para este aluno em ' . date('Y') . '.']);
            }

        return view('secretaria.documentos.create', compact('aluno', 'type'));
    }

    public function store(Student $aluno, Request $request)
    {
        $request->validate([
            'type' => 'required|in:estudo_caso,pei,paee',
        ]);

        $type = $request->input('type');

        if (in_array($type, ['pei', 'paee'])) {
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
            'status'     => 'draft',
            'content'    => $content,
        ]);

        if ($type === 'estudo_caso') {
            $aluno->update(['has_case_study' => true]);
        }

        return redirect()->route('secretaria.alunos.show', $aluno)
            ->with('success', strtoupper($type) . ' criado com sucesso.');
    }

    public function show(Document $documento)
    {
        $documento->load('student', 'author');
        return view('secretaria.documentos.show', compact('documento'));
    }

    public function edit(Document $documento)
    {
        $documento->load('student');
        return view('secretaria.documentos.edit', compact('documento'));
    }

    public function update(Request $request, Document $documento)
    {
        $content = DocumentContentService::buildContent($documento->type, $request);

        $documento->update([
            'content' => $content,
            'status'  => $request->input('status', $documento->status),
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
        }

        return redirect()->route('secretaria.alunos.show', $student)
            ->with('success', 'Documento removido.');
    }
}