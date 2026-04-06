<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
public function index(\Illuminate\Http\Request $request)
{
    $ano = date('Y');
    $query = Student::with(['schoolClasses' => fn($q) => $q->where('year', $ano)])->latest();

    if ($request->filled('turma')) {
        $query->whereHas('schoolClasses', fn($q) => $q->where('school_classes.id', $request->input('turma')));
    }

    if ($request->filled('perfil')) {
        match ($request->input('perfil')) {
            'atipico'     => $query->where('is_atypical', true),
            'tipico'      => $query->where('is_atypical', false),
            'tea'         => $query->where('cid_autismo', true),
            'tdah'        => $query->where('cid_tdah', true),
            'down'        => $query->where('cid_down', true),
            'intelectual' => $query->where('cid_deficiencia_intelectual', true),
            'visual'      => $query->where('cid_deficiencia_visual', true),
            'auditiva'    => $query->where('cid_deficiencia_auditiva', true),
            default       => null,
        };
    }

    $alunos = $query->get();
    $turmas = SchoolClass::where('year', $ano)->orderBy('name')->get();

    return view('secretaria.alunos.index', compact('alunos', 'turmas'));
}
    public function create()
    {
        $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get();
        return view('secretaria.alunos.create', compact('turmas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                        => 'required|string|max:255',
            'registration_number'         => ['required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('students')->where('school_id', session('school_id'))],
            'birth_date'                  => 'required|date',
            'responsavel_nome'            => 'nullable|string|max:255',
            'responsavel_2_nome'          => 'nullable|string|max:255',
            'is_atypical'                 => 'boolean',
            'condition'                   => 'nullable|string|max:255',
            'school_class_id'             => 'nullable|exists:school_classes,id',
            'tea_nivel_suporte'           => 'nullable|in:1,2,3',
            'photo'                       => 'nullable|image|max:2048',
        ]);

        $data['school_id'] = session('school_id');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('alunos/fotos', 'public');
        }

        $data['is_atypical']       = $request->boolean('is_atypical');
        $data['tea_nivel_suporte'] = $request->boolean('cid_autismo') ? $request->input('tea_nivel_suporte') : null;

        foreach (array_keys(config('transtornos')) as $campo) {
            $data[$campo] = $request->boolean($campo);
        }

        $school = \App\Models\School::find(session('school_id'));
        $currentCount = \App\Models\Student::count();
        if ($currentCount >= $school->max_students) {
            return back()->withErrors([
                'limit' => "Limite de {$school->max_students} alunos atingido para o plano {$school->plan}."
            ]);
        }

        $aluno = Student::create($data);

        if ($request->filled('school_class_id')) {
            $aluno->schoolClasses()->attach($request->school_class_id);
        }

        // Redireciona para a turma se veio de lá
        if ($request->filled('school_class_id')) {
            return redirect()->route('secretaria.turmas.show', $request->school_class_id)
                ->with('success', 'Aluno cadastrado com sucesso.');
        }

        return redirect()->route('secretaria.alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso.');
    }

public function show(Student $aluno)
{
    $aluno->load([
        'schoolClasses:id,name,shift,year',
        'documents' => fn($q) => $q->where('year', date('Y'))->with('author:id,name'),
        'observations' => fn($q) => $q->with('user:id,name')->latest()->take(20),
    ]);

    $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get(['id','name','shift']);

    return view('secretaria.alunos.show', compact('aluno', 'turmas'));
}

    public function edit(Student $aluno)
    {
        $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get();
        return view('secretaria.alunos.edit', compact('aluno', 'turmas'));
    }

    public function update(Request $request, Student $aluno)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'registration_number' => [
                'required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('students')
                    ->where('school_id', session('school_id'))
                    ->ignore($aluno->id),
            ],
            'birth_date'          => 'required|date',
            'responsavel_nome'    => 'nullable|string|max:255',
            'responsavel_2_nome'  => 'nullable|string|max:255',
            'is_atypical'         => 'boolean',
            'condition'           => 'nullable|string|max:255',
            'tea_nivel_suporte'   => 'nullable|in:1,2,3',
            'photo'               => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($aluno->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($aluno->photo);
            }
            $data['photo'] = $request->file('photo')->store('alunos/fotos', 'public');
        }

        $data['is_atypical']       = $request->boolean('is_atypical');
        $data['tea_nivel_suporte'] = $request->boolean('cid_autismo') ? $request->input('tea_nivel_suporte') : null;

        foreach (array_keys(config('transtornos')) as $campo) {
            $data[$campo] = $request->boolean($campo);
        }

        $aluno->update($data);

        return redirect()->route('secretaria.alunos.show', $aluno)
            ->with('success', 'Aluno atualizado com sucesso.');
    }

    public function destroy(Student $aluno)
    {
        if ($aluno->documents()->exists() || $aluno->laudos()->exists()) {
            return back()->with('error', 'Não é possível remover o aluno pois ele possui documentos ou laudos cadastrados.');
        }

        $aluno->delete();
        return redirect()->route('secretaria.alunos.index')
            ->with('success', 'Aluno removido.');
    }

    public function uploadPhoto(Request $request, Student $aluno)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        if ($aluno->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($aluno->photo);
        }

        $path = $request->file('photo')->store('alunos/fotos', 'public');
        $aluno->update(['photo' => $path]);

        return back()->with('success', 'Foto atualizada com sucesso.');
    }

    public function attachClass(Request $request, Student $aluno)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
        ]);

        $aluno->schoolClasses()->sync([$request->school_class_id]);

        return back()->with('success', 'Turma vinculada com sucesso.');
    }
}