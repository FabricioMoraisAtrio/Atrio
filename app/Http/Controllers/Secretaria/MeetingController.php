<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    /** Lista de reuniões do aluno. */
    public function index(Student $aluno)
    {
        $reunioes = $aluno->meetings()->with('creator')->orderByDesc('data')->orderByDesc('id')->get();

        return view('secretaria.reunioes.index', compact('aluno', 'reunioes'));
    }

    public function create(Student $aluno)
    {
        $reuniao = new Meeting(['data' => now()->toDateString()]);

        return view('secretaria.reunioes.form', compact('aluno', 'reuniao'));
    }

    public function store(Request $request, Student $aluno)
    {
        $dados = $this->validar($request);

        $aluno->meetings()->create($dados + [
            'school_id'  => session('school_id'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('secretaria.alunos.reunioes.index', $aluno)
            ->with('success', 'Reunião registrada.');
    }

    public function edit(Student $aluno, Meeting $reuniao)
    {
        $this->garantirDono($aluno, $reuniao);

        return view('secretaria.reunioes.form', compact('aluno', 'reuniao'));
    }

    public function update(Request $request, Student $aluno, Meeting $reuniao)
    {
        $this->garantirDono($aluno, $reuniao);

        $reuniao->update($this->validar($request));

        return redirect()->route('secretaria.alunos.reunioes.index', $aluno)
            ->with('success', 'Reunião atualizada.');
    }

    public function destroy(Student $aluno, Meeting $reuniao)
    {
        $this->garantirDono($aluno, $reuniao);

        $reuniao->delete();

        return redirect()->route('secretaria.alunos.reunioes.index', $aluno)
            ->with('success', 'Reunião excluída.');
    }

    /** Impede acessar reunião de outro aluno via URL. */
    private function garantirDono(Student $aluno, Meeting $reuniao): void
    {
        abort_unless($reuniao->student_id === $aluno->id, 404);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'data'            => 'required|date',
            'tipo'            => 'required|in:' . implode(',', array_keys(Meeting::TIPOS)),
            'participantes'   => 'required|string|max:2000',
            'pauta'           => 'nullable|string|max:5000',
            'encaminhamentos' => 'nullable|string|max:5000',
            'observacoes'     => 'nullable|string|max:5000',
        ]);
    }
}
