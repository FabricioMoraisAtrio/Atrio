<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Laudo;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaudoController extends Controller
{
    public function store(Request $request, Student $aluno)
    {
        $request->validate([
            'tipo'       => 'required|in:medico,psicologico,fonoaudiologico,neuropediatrico,outro',
            'data_laudo' => 'required|date',
            'descricao'  => 'nullable|string|max:255',
            'arquivo'    => 'required|file|mimes:pdf|max:10240',
        ]);

        $path = $request->file('arquivo')->store('laudos/' . $aluno->id, 'private');

        Laudo::create([
            'school_id'   => session('school_id'),
            'student_id'  => $aluno->id,
            'uploaded_by' => auth()->id(),
            'tipo'        => $request->tipo,
            'descricao'   => $request->descricao,
            'arquivo'     => $path,
            'data_laudo'  => $request->data_laudo,
        ]);

        return back()->with('success', 'Laudo enviado com sucesso.');
    }

    public function download(Laudo $laudo)
    {
        abort_unless($laudo->school_id === session('school_id'), 403);

        return Storage::disk('private')->download(
            $laudo->arquivo,
            $laudo->tipo_label . '_' . $laudo->student->name . '_' . $laudo->data_laudo->format('d-m-Y') . '.pdf'
        );
    }

    public function destroy(Laudo $laudo)
    {
        abort_unless($laudo->school_id === session('school_id'), 403);

        Storage::disk('private')->delete($laudo->arquivo);
        $laudo->delete();

        return back()->with('success', 'Laudo removido.');
    }

        public function index()
    {
        $laudos = \App\Models\Laudo::with('student', 'uploader')
            ->latest()
            ->get()
            ->groupBy('tipo');

        return view('secretaria.laudos.index', compact('laudos'));
    }
}