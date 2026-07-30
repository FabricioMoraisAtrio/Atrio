<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $year   = date('Y');
        $turmas = SchoolClass::where('school_id', session('school_id'))
            ->withCount('students')
            ->with([
                'students' => fn($q) => $q->orderBy('name')->with([
                    'documents' => fn($d) => $d->whereYear('created_at', $year)
                        ->whereIn('type', ['estudo_caso', 'paee', 'pei', 'pei_consolidado']),
                    'laudos',
                ]),
            ])
            ->orderBy('name')
            ->get();
        return view('secretaria.turmas.index', compact('turmas'));
    }

    public function create()
    {
        return view('secretaria.turmas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('school_classes')->where(fn ($q) => $q
                    ->where('school_id', session('school_id'))
                    ->where('shift', $request->input('shift'))
                    ->where('year', $request->input('year'))),
            ],
            'shift' => 'required|in:Matutino,Vespertino,Noturno',
            'year'  => 'required|digits:4',
        ], [
            'name.unique' => 'Já existe uma turma com esse nome para o mesmo turno e ano.',
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
            'name'  => ['required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('school_classes')->ignore($turma->id)->where(fn ($q) => $q
                    ->where('school_id', session('school_id'))
                    ->where('shift', $request->input('shift'))
                    ->where('year', $request->input('year'))),
            ],
            'shift' => 'required|in:Matutino,Vespertino,Noturno',
            'year'  => 'required|digits:4',
        ], [
            'name.unique' => 'Já existe uma turma com esse nome para o mesmo turno e ano.',
        ]);

        $turma->update($data);

        return redirect()->route('secretaria.turmas.index')
            ->with('success', 'Turma atualizada com sucesso.');
    }

    public function destroy(SchoolClass $turma)
    {
        if ($turma->students()->exists()) {
            return back()->with('error', 'Não é possível remover a turma pois ela possui alunos vinculados.');
        }

        $turma->delete();
        return redirect()->route('secretaria.turmas.index')
            ->with('success', 'Turma removida.');
    }
    public function show(SchoolClass $turma)
    {
        $year = date('Y');
        $turma->load([
            'teachers',
            'students.documents' => fn($q) => $q->whereYear('created_at', $year)
                ->whereIn('type', ['estudo_caso', 'paee', 'pei', 'pei_consolidado']),
            'students.laudos',
        ]);
        return view('secretaria.turmas.show', compact('turma'));
    }
}