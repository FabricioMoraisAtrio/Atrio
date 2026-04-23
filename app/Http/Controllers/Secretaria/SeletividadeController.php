<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentFoodItem;
use Illuminate\Http\Request;

class SeletividadeController extends Controller
{
    public function index()
    {
        $schoolId = session('school_id');

        $alunos = Student::with(['foodItems', 'schoolClasses' => fn($q) => $q->where('year', date('Y'))->select('school_classes.id', 'name')])
            ->where('is_atypical', true)
            ->orderBy('name')
            ->get();

        return view('secretaria.seletividade.index', compact('alunos'));
    }

    public function show(Student $aluno)
    {
        $aluno->load(['foodItems', 'schoolClasses' => fn($q) => $q->where('year', date('Y'))->select('school_classes.id', 'name')]);

        $categories = StudentFoodItem::CATEGORIES;
        $statuses   = StudentFoodItem::STATUSES;
        $byCategory = $aluno->foodItems->groupBy('category');

        return view('secretaria.seletividade.show', compact('aluno', 'categories', 'statuses', 'byCategory'));
    }

    public function store(Request $request, Student $aluno)
    {
        $data = $request->validate([
            'category' => ['required', 'in:' . implode(',', array_keys(StudentFoodItem::CATEGORIES))],
            'name'     => ['required', 'string', 'max:100'],
            'status'   => ['required', 'in:aceita,tolera,recusa'],
            'notes'    => ['nullable', 'string', 'max:200'],
        ]);

        $aluno->foodItems()->create([
            ...$data,
            'school_id' => session('school_id'),
        ]);

        return back()->with('success', 'Alimento registrado.');
    }

    public function destroy(StudentFoodItem $item)
    {
        $item->delete();
        return back()->with('success', 'Alimento removido.');
    }

    public function exportIndividual(Student $aluno)
    {
        $school = School::find(session('school_id'));
        $aluno->load([
            'foodItems',
            'schoolClasses' => fn($q) => $q->where('year', date('Y'))->select('school_classes.id', 'name'),
        ]);

        $categories = StudentFoodItem::CATEGORIES;
        $statuses   = StudentFoodItem::STATUSES;
        $byStatus   = $aluno->foodItems->groupBy('status');
        $byCategory = $aluno->foodItems->groupBy('category');

        return view('secretaria.seletividade.export-individual', compact('aluno', 'school', 'categories', 'statuses', 'byStatus', 'byCategory'));
    }

    public function export()
    {
        $school = School::find(session('school_id'));

        $alunos = Student::with([
                'foodItems',
                'schoolClasses' => fn($q) => $q->where('year', date('Y'))->select('school_classes.id', 'name'),
            ])
            ->where('is_atypical', true)
            ->orderBy('name')
            ->get()
            ->filter(fn($a) => $a->foodItems->isNotEmpty());

        $categories = StudentFoodItem::CATEGORIES;

        return view('secretaria.seletividade.export', compact('alunos', 'school', 'categories'));
    }
}
