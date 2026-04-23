<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Subject;
use App\Models\SubjectInventoryItem;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;

class SchoolSubjectController extends Controller
{
    public function store(Request $request, School $school)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'slug'              => 'required|string|max:60|alpha_dash',
            'label_responsavel' => 'required|string|max:60',
            'tipo'              => 'required|in:disciplina,regente',
            'ordem'             => 'nullable|integer|min:0',
        ]);

        Subject::withoutGlobalScope(SchoolScope::class)->create(array_merge($data, [
            'school_id' => $school->id,
            'ordem'     => $data['ordem'] ?? 0,
        ]));

        return redirect()->route('admin.schools.edit', $school)
            ->with('success', 'Matéria criada.');
    }

    public function update(Request $request, School $school, Subject $subject)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'label_responsavel' => 'required|string|max:60',
            'tipo'              => 'required|in:disciplina,regente',
            'ordem'             => 'nullable|integer|min:0',
        ]);

        $subject->withoutGlobalScope(SchoolScope::class)
            ->update(array_merge($data, ['ordem' => $data['ordem'] ?? $subject->ordem]));

        return redirect()->route('admin.schools.edit', $school)
            ->with('success', 'Matéria atualizada.');
    }

    public function destroy(School $school, Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.schools.edit', $school)
            ->with('success', 'Matéria removida.');
    }
}
