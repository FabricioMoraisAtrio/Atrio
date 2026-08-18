<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\DocumentAccessLog;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
public function index(\Illuminate\Http\Request $request)
{
    $ano = date('Y');
    // Escopo: quem não tem alunos.ver_todos (professor) vê só os das próprias turmas.
    $query = Student::visiveisPara(auth()->user())
        ->with(['schoolClasses' => fn($q) => $q->where('year', $ano)])->latest();

    if ($request->filled('turma')) {
        $query->whereHas('schoolClasses', fn($q) => $q->where('school_classes.id', $request->input('turma')));
    }

    if ($request->filled('perfil')) {
        match ($request->input('perfil')) {
            'atipico'      => $query->where('is_atypical', true),
            'tipico'       => $query->where('is_atypical', false),
            'publico_alvo' => $query->where(fn($q) => collect(Student::PUBLICO_ALVO_FIELDS)
                ->reduce(fn($q, $f) => $q->orWhere($f, true), $q)),
            'tea'          => $query->where('cid_autismo', true),
            'tgd'          => $query->where('cid_tgd', true),
            'tdah'         => $query->where('cid_tdah', true),
            'down'         => $query->where('cid_down', true),
            'intelectual'  => $query->where('cid_deficiencia_intelectual', true),
            'visual'       => $query->where('cid_deficiencia_visual', true),
            'auditiva'     => $query->where('cid_deficiencia_auditiva', true),
            default        => null,
        };
    }

    $alunos = $query->with([
        'documents' => fn($q) => $q->where('year', $ano)->select('id','student_id','type','year'),
        'laudos'    => fn($q) => $q->select('id','student_id'),
    ])->get();
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
            'responsavel_email'           => 'nullable|email|max:255',
            'responsavel_telefone'        => 'nullable|string|max:30',
            'responsavel_2_nome'          => 'nullable|string|max:255',
            'responsavel_2_email'         => 'nullable|email|max:255',
            'responsavel_2_telefone'      => 'nullable|string|max:30',
            'is_atypical'                 => 'boolean',
            'condition'                   => 'nullable|string|max:255',
            'nivel_bloom'                 => 'nullable|in:lembrar,entender,aplicar,analisar,avaliar,criar',
            'school_class_id'             => 'nullable|exists:school_classes,id',
            'tea_nivel_suporte'           => 'nullable|in:1,2,3',
            'photo'                       => 'nullable|image|max:2048',
            'laudo_tipo'                  => 'required_with:laudo_arquivo|nullable|in:medico,psicologico,fonoaudiologico,neuropediatrico,outro',
            'laudo_data_laudo'            => 'required_with:laudo_arquivo|nullable|date',
            'laudo_descricao'             => 'nullable|string|max:255',
            'laudo_arquivo'               => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $school = \App\Models\School::find(session('school_id'));
        $currentCount = \App\Models\Student::count();
        if ($currentCount >= $school->max_students) {
            return back()->withErrors([
                'limit' => "Limite de {$school->max_students} estudantes atingido para o plano {$school->plan}."
            ]);
        }

        $data['school_id'] = session('school_id');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('alunos/fotos', 'public');
        }

        $data['tea_nivel_suporte'] = $request->boolean('cid_autismo') ? $request->input('tea_nivel_suporte') : null;

        $anyCid = false;
        foreach (array_keys(config('transtornos')) as $campo) {
            $data[$campo] = $request->boolean($campo);
            if ($data[$campo]) $anyCid = true;
        }
        // is_atypical = true se qualquer CID marcado ou checkbox manual
        $data['is_atypical'] = $anyCid || $request->boolean('is_atypical');

        unset($data['laudo_tipo'], $data['laudo_data_laudo'], $data['laudo_descricao'], $data['laudo_arquivo']);

        $aluno = Student::create($data);

        if ($request->filled('school_class_id')) {
            $aluno->schoolClasses()->attach($request->school_class_id);
        }

        DocumentAccessLog::record('created', [
            'student_id'    => $aluno->id,
            'student_name'  => $aluno->name,
            'document_type' => 'aluno',
        ]);

        if ($request->hasFile('laudo_arquivo')) {
            $laudoPath = $request->file('laudo_arquivo')->store('laudos/' . $aluno->id, 'private');

            \App\Models\Laudo::create([
                'school_id'   => session('school_id'),
                'student_id'  => $aluno->id,
                'uploaded_by' => auth()->id(),
                'tipo'        => $request->input('laudo_tipo'),
                'descricao'   => $request->input('laudo_descricao'),
                'arquivo'     => $laudoPath,
                'data_laudo'  => $request->input('laudo_data_laudo'),
            ]);

            DocumentAccessLog::record('created', [
                'student_id'    => $aluno->id,
                'student_name'  => $aluno->name,
                'document_type' => 'laudo',
            ]);
        }

        return redirect()->route('secretaria.alunos.index')
            ->with('success', 'Estudante cadastrado com sucesso.');
    }

public function show(Student $aluno)
{
    abort_unless(auth()->user()->podeAcessarEstudante($aluno), 403);

    $aluno->load([
        'schoolClasses:id,name,shift,year',
        'documents' => fn($q) => $q->with('author:id,name'), // todos os anos (histórico)
        'observations' => fn($q) => $q->with('user:id,name')->latest()->take(20),
        'foodItems',
    ]);

    $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get(['id','name','shift']);

    return view('secretaria.alunos.show', compact('aluno', 'turmas'));
}

    public function edit(Student $aluno)
    {
        $aluno->load('schoolClasses');
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
            'responsavel_email'   => 'nullable|email|max:255',
            'responsavel_telefone'=> 'nullable|string|max:30',
            'responsavel_2_nome'  => 'nullable|string|max:255',
            'responsavel_2_email' => 'nullable|email|max:255',
            'responsavel_2_telefone' => 'nullable|string|max:30',
            'is_atypical'         => 'boolean',
            'condition'           => 'nullable|string|max:255',
            'nivel_bloom'         => 'nullable|in:lembrar,entender,aplicar,analisar,avaliar,criar',
            'tea_nivel_suporte'   => 'nullable|in:1,2,3',
            'photo'               => 'nullable|image|max:2048',
            'school_class_id'     => 'nullable|exists:school_classes,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($aluno->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($aluno->photo);
            }
            $data['photo'] = $request->file('photo')->store('alunos/fotos', 'public');
        } elseif ($request->boolean('remove_photo') && $aluno->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($aluno->photo);
            $data['photo'] = null;
        }

        $data['tea_nivel_suporte'] = $request->boolean('cid_autismo') ? $request->input('tea_nivel_suporte') : null;

        $anyCid = false;
        foreach (array_keys(config('transtornos')) as $campo) {
            $data[$campo] = $request->boolean($campo);
            if ($data[$campo]) $anyCid = true;
        }
        // is_atypical = true se qualquer CID marcado ou checkbox manual
        $data['is_atypical'] = $anyCid || $request->boolean('is_atypical');

        $classId = $data['school_class_id'] ?? null;
        unset($data['school_class_id']);

        $aluno->update($data);

        if ($classId) {
            $aluno->schoolClasses()->sync([$classId]);
        }

        DocumentAccessLog::record('edited', [
            'student_id'    => $aluno->id,
            'student_name'  => $aluno->name,
            'document_type' => 'aluno',
        ]);

        return redirect()->route('secretaria.alunos.index')
            ->with('success', 'Estudante atualizado com sucesso.');
    }

    public function destroy(Student $aluno)
    {
        if ($aluno->documents()->exists() || $aluno->laudos()->exists()) {
            return back()->with('error', 'Não é possível remover o estudante pois ele possui documentos ou laudos cadastrados.');
        }

        // Registra antes de excluir (student_id ainda válido para a FK)
        DocumentAccessLog::record('deleted', [
            'student_id'    => $aluno->id,
            'student_name'  => $aluno->name,
            'document_type' => 'aluno',
        ]);

        $aluno->delete();
        return redirect()->route('secretaria.alunos.index')
            ->with('success', 'Estudante removido.');
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

    public function removePhoto(Student $aluno)
    {
        if ($aluno->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($aluno->photo);
            $aluno->update(['photo' => null]);
        }

        return back()->with('success', 'Foto removida.');
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