<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectInventoryItem;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('inventoryItems')->orderBy('ordem')->get();
        return view('secretaria.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('secretaria.subjects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'slug'              => 'required|string|max:60|alpha_dash',
            'label_responsavel' => 'required|string|max:60',
            'tipo'              => 'required|in:disciplina,regente',
            'ordem'             => 'nullable|integer|min:0',
        ]);

        $schoolId = auth()->user()->school_id;

        $subject = Subject::create(array_merge($data, [
            'school_id' => $schoolId,
            'ordem'     => $data['ordem'] ?? 0,
        ]));

        return redirect()->route('secretaria.subjects.show', $subject)
            ->with('success', 'Matéria criada com sucesso.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['inventoryItems' => fn($q) => $q->orderBy('ordem')]);
        return view('secretaria.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('secretaria.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'label_responsavel' => 'required|string|max:60',
            'tipo'              => 'required|in:disciplina,regente',
            'ordem'             => 'nullable|integer|min:0',
        ]);

        $subject->update(array_merge($data, ['ordem' => $data['ordem'] ?? $subject->ordem]));

        return redirect()->route('secretaria.subjects.show', $subject)
            ->with('success', 'Matéria atualizada.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('secretaria.subjects.index')
            ->with('success', 'Matéria removida.');
    }

    // Salva as metas do inventário (na página show)
    public function saveItems(Request $request, Subject $subject)
    {
        $request->validate([
            'metas'               => 'nullable|array',
            'metas.*.texto'       => 'required|string|max:255',
            'metas.*.categoria'   => 'required|in:academica,socioemocional,global',
        ]);

        $schoolId = auth()->user()->school_id;
        $subject->inventoryItems()->delete();

        foreach (($request->metas ?? []) as $i => $meta) {
            if (trim($meta['texto']) === '') continue;
            SubjectInventoryItem::create([
                'school_id'  => $schoolId,
                'subject_id' => $subject->id,
                'meta'       => trim($meta['texto']),
                'categoria'  => $meta['categoria'],
                'ordem'      => $i + 1,
            ]);
        }

        return redirect()->route('secretaria.subjects.show', $subject)
            ->with('success', 'Metas atualizadas.');
    }
}
