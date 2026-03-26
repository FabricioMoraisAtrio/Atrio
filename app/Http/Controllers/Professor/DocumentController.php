<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Services\DocumentContentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documentos = Document::with('student')
            ->where('author_id', auth()->id())
            ->latest()
            ->get()
            ->groupBy('type');

        return view('professor.documentos.index', compact('documentos'));
    }

private function verificarAcesso(int $studentId): void
{
    $turmasDoProfesor = auth()->user()->schoolClasses()->pluck('school_classes.id');

    $alunoNaTurma = \App\Models\SchoolClass::withoutGlobalScopes()
        ->whereIn('id', $turmasDoProfesor)
        ->whereHas('students', fn($q) => $q->withoutGlobalScopes()->where('students.id', $studentId))
        ->exists();

    if (! $alunoNaTurma) {
        abort(403);
    }
}
    public function create(Student $aluno, Request $request)
    {
        $this->verificarAcesso($aluno->id);

        $type = $request->query('type', 'estudo_caso');

        if (in_array($type, ['pei', 'paee'])) {
            $hasCase = Document::where('student_id', $aluno->id)
                ->where('type', 'estudo_caso')
                ->where('year', date('Y'))
                ->exists();

            if (! $hasCase) {
                return back()->withErrors(['documento' => 'Preencha o Estudo de Caso antes de criar PEI ou PAEE.']);
            }
        }

        $exists = Document::where('student_id', $aluno->id)
            ->where('type', $type)
            ->where('year', date('Y'))
            ->exists();

        if ($exists) {
            return back()->withErrors(['documento' => 'Já existe um ' . strtoupper($type) . ' para este aluno em ' . date('Y') . '.']);
        }

        return view('professor.documentos.create', compact('aluno', 'type'));
    }

public function store(Student $aluno, Request $request)
{
    $this->verificarAcesso($aluno->id);

    $request->validate([
        'type' => 'required|in:estudo_caso,pei,paee',
    ]);

    $type = $request->input('type'); // $type definido AQUI

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

    return redirect()->route('professor.dashboard')
        ->with('success', strtoupper($type) . ' criado com sucesso.');
        // Notifica pais do aluno
    $aluno->load('parents');
    foreach ($aluno->parents as $pai) {
        $pai->notify(new \App\Notifications\NovoDocumentoPaiNotification($documento));
    }
}

    public function show(Document $documento)
    {
        $this->verificarAcesso($documento->student_id);
        $documento->load('student', 'author');
        return view('professor.documentos.show', compact('documento'));
    }

public function edit(Document $documento)
{
    $this->verificarAcesso($documento->student_id);
    $documento->load('student');
    return view('professor.documentos.edit', compact('documento'));
}

public function update(Request $request, Document $documento)
{
    $this->verificarAcesso($documento->student_id);

    $content = DocumentContentService::buildContent($documento->type, $request);

    $documento->update(['content' => $content]);

    return redirect()->route('professor.documentos.show', $documento)
        ->with('success', 'Documento atualizado.');
}
}