<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $turmas = SchoolClass::withCount('students')->latest()->get();
        return view('secretaria.turmas.index', compact('turmas'));
    }

    public function create()
    {
        return view('secretaria.turmas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:50',
            'shift' => 'required|in:Matutino,Vespertino,Noturno',
            'year'  => 'required|digits:4',
        ]);

        $data['school_id'] = session('school_id');

        SchoolClass::create($data);

        return redirect()->route('secretaria.turmas.index')
            ->with('success', 'Turma criada com sucesso.');
    }

    public function edit(SchoolClass $turma)
    {
        return view('secretaria.turmas.edit', compact('turma'));
    }

    public function update(Request $request, SchoolClass $turma)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:50',
            'shift' => 'required|in:Matutino,Vespertino,Noturno',
            'year'  => 'required|digits:4',
        ]);

        $turma->update($data);

        return redirect()->route('secretaria.turmas.index')
            ->with('success', 'Turma atualizada com sucesso.');
    }

    public function destroy(SchoolClass $turma)
    {
        $turma->delete();
        return redirect()->route('secretaria.turmas.index')
            ->with('success', 'Turma removida.');
    }
    public function show(SchoolClass $turma)
{
    $turma->load(['students', 'teachers']);
    return view('secretaria.turmas.show', compact('turma'));
}
}