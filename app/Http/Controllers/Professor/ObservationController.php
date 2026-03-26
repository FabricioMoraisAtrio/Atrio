<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Observation;
use App\Models\Student;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    public function store(Request $request, Student $aluno)
    {
        // Professor só observa alunos das suas turmas
        $temAcesso = \App\Models\SchoolClass::withoutGlobalScopes()
            ->whereIn('id', auth()->user()->schoolClasses()->pluck('school_classes.id'))
            ->whereHas('students', fn($q) => $q->withoutGlobalScopes()->where('students.id', $aluno->id))
            ->exists();

        if (! $temAcesso) abort(403);

        $request->validate([
            'content'  => 'required|string|max:1000',
            'urgency'  => 'required|in:normal,atencao,critico',
            'category' => 'required|in:comportamento,aprendizado,saude',
        ]);

        Observation::create([
            'school_id'  => session('school_id'),
            'student_id' => $aluno->id,
            'user_id'    => auth()->id(),
            'content'    => $request->content,
            'urgency'    => $request->urgency,
            'category'   => $request->category,
        ]);

        return back()->with('success', 'Observação registrada.');

                // Notifica secretaria se observação for crítica
        if ($request->urgency === 'critico') {
            $observation = \App\Models\Observation::with('student', 'user')
                ->where('student_id', $aluno->id)
                ->latest()->first();

            $secretarias = \App\Models\User::where('school_id', session('school_id'))
                ->where('notify_document_pending', true)
                ->whereHas('roles', fn($q) => $q->where('name', 'secretaria'))
                ->get();

            foreach ($secretarias as $usuario) {
                $usuario->notify(new \App\Notifications\ObservacaoCriticaNotification($observation));
            }
        }
    }

    public function destroy(Observation $observation)
    {
        if ($observation->user_id !== auth()->id()) abort(403);
        $observation->delete();
        return back()->with('success', 'Observação removida.');
    }
}