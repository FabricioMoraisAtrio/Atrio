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
    $query = Student::with(['schoolClasses:id,name,shift,year'])->latest();

    if ($request->filled('adaptacao')) {
        $tag = $request->input('adaptacao');
        $query->whereHas('documents', function ($q) use ($tag) {
            $q->where('type', 'pei')
              ->whereRaw("json_extract(content, '$.adaptacoes') LIKE ?", ['%' . $tag . '%']);
        });
    }

    if ($request->filled('materia')) {
        $materia = $request->input('materia');
        $query->whereHas('documents', function ($q) use ($materia) {
            $q->where('type', 'pei')
              ->whereRaw("json_extract(content, '$.materia') = ?", [$materia]);
        });
    }

    if ($request->filled('perfil')) {
        $perfil = $request->input('perfil');
        match ($perfil) {
            'atipico'  => $query->where('is_atypical', true),
            'tipico'   => $query->where('is_atypical', false),
            'tea'      => $query->where('cid_autismo', true),
            'tdah'     => $query->where('cid_tdah', true),
            'down'     => $query->where('cid_down', true),
            'intelectual' => $query->where('cid_deficiencia_intelectual', true),
            'visual'   => $query->where('cid_deficiencia_visual', true),
            'auditiva' => $query->where('cid_deficiencia_auditiva', true),
            default    => null,
        };
    }

    $alunos = $query->get();

    $todasAdaptacoes = [
        'Tempo extra na prova', 'Prova com fonte ampliada', 'Prova com imagens de apoio',
        'Avaliação oral', 'Redução de questões', 'Questões objetivas (sem dissertativas)',
        'Material concreto/manipulativo', 'Texto de apoio', 'Prova em braille',
        'Intérprete de Libras', 'Sala separada', 'Leitura em voz alta pelo professor',
        'Uso de calculadora', 'Apoio de escriba', 'Adaptação de conteúdo',
        'Gravação de resposta (áudio)', 'Prova digitalizada', 'Sem limite de tempo',
    ];

    $materias = \App\Models\Document::where('type', 'pei')
        ->whereNotNull('content')
        ->get()
        ->pluck('content.materia')
        ->filter()
        ->unique()
        ->sort()
        ->values();

    return view('secretaria.alunos.index', compact('alunos', 'todasAdaptacoes', 'materias'));
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
            'is_atypical'                 => 'boolean',
            'condition'                   => 'nullable|string|max:255',
            'school_class_id'             => 'nullable|exists:school_classes,id',
            'tea_nivel_suporte'           => 'nullable|in:1,2,3',
            'cid_autismo'                 => 'boolean',
            'cid_tdah'                    => 'boolean',
            'cid_down'                    => 'boolean',
            'cid_deficiencia_intelectual' => 'boolean',
            'cid_deficiencia_visual'      => 'boolean',
            'cid_deficiencia_auditiva'    => 'boolean',
            'cid_outros'                  => 'boolean',
        ]);

        $data['school_id']                    = session('school_id');
        $data['is_atypical']                  = $request->boolean('is_atypical');
        $data['cid_autismo']                  = $request->boolean('cid_autismo');
        $data['tea_nivel_suporte']            = $request->boolean('cid_autismo') ? $request->input('tea_nivel_suporte') : null;
        $data['cid_tdah']                     = $request->boolean('cid_tdah');
        $data['cid_down']                     = $request->boolean('cid_down');
        $data['cid_deficiencia_intelectual']  = $request->boolean('cid_deficiencia_intelectual');
        $data['cid_deficiencia_visual']       = $request->boolean('cid_deficiencia_visual');
        $data['cid_deficiencia_auditiva']     = $request->boolean('cid_deficiencia_auditiva');
        $data['cid_outros']                   = $request->boolean('cid_outros');

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
            'required',
            'string',
            'max:50',
            \Illuminate\Validation\Rule::unique('students')
                ->where('school_id', session('school_id'))
                ->ignore($aluno->id),],
            'birth_date'          => 'required|date',
            'is_atypical'         => 'boolean',
            'condition'           => 'nullable|string|max:255',
            'tea_nivel_suporte'   => 'nullable|in:1,2,3',
        ]);

        $data['is_atypical'] = $request->boolean('is_atypical');
        $data['cid_autismo']                 = $request->boolean('cid_autismo');
        $data['tea_nivel_suporte']           = $request->boolean('cid_autismo') ? $request->input('tea_nivel_suporte') : null;
        $data['cid_tdah']                    = $request->boolean('cid_tdah');
        $data['cid_down']                    = $request->boolean('cid_down');
        $data['cid_deficiencia_intelectual'] = $request->boolean('cid_deficiencia_intelectual');
        $data['cid_deficiencia_visual']      = $request->boolean('cid_deficiencia_visual');
        $data['cid_deficiencia_auditiva']    = $request->boolean('cid_deficiencia_auditiva');
        $data['cid_outros']                  = $request->boolean('cid_outros');
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

    public function attachClass(Request $request, Student $aluno)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
        ]);

        $aluno->schoolClasses()->sync([$request->school_class_id]);

        return back()->with('success', 'Turma vinculada com sucesso.');
    }
}