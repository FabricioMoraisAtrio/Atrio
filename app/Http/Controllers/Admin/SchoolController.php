<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
    public function index()
    {
        // Auto-suspende escolas com plano expirado
        School::where('plan_status', 'active')
            ->whereNotNull('plan_expires_at')
            ->where('plan_expires_at', '<', now())
            ->update(['plan_status' => 'suspended']);

        $schools = School::withCount(['users', 'students'])
            ->latest()->get();

        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'slug'            => 'required|string|max:100|unique:schools,slug',
            'plan'            => 'required|in:pro,enterprise',
            'plan_expires_at' => 'required|date',
            'max_students'    => 'required|integer|min:1',
            'notes'           => 'nullable|string',
            'logo'            => 'nullable|file|mimes:svg,png,jpg,jpeg|max:2048',
            'theme_color'     => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            // Secretaria inicial
            'admin_name'      => 'required|string|max:255',
            'admin_email'     => 'required|email|unique:users,email',
            'admin_password'  => 'required|min:6',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $school = School::create([
            'name'            => $data['name'],
            'slug'            => $data['slug'],
            'plan'            => $data['plan'],
            'plan_status'     => 'active',
            'plan_expires_at' => $data['plan_expires_at'],
            'max_students'    => $data['max_students'],
            'notes'           => $data['notes'] ?? null,
            'is_active'       => true,
            'logo'            => $logoPath,
            'theme_color'     => $data['theme_color'] ?? null,
            'modules'         => [],
        ]);

        $adminUser = User::create([
            'name'      => $data['admin_name'],
            'email'     => $data['admin_email'],
            'password'  => Hash::make($data['admin_password']),
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        $adminUser->assignRole('admin');

        // Semeia a grade curricular padrão (BNCC + professor regente)
        \App\Models\Subject::seedDefaultsForSchool($school->id);

        return redirect()->route('admin.schools.index')
            ->with('success', 'Escola criada com sucesso.');
    }

    public function show(School $school)
    {
        $school->loadCount(['users', 'students', 'schoolClasses'])
               ->load('users.roles');

        $documentCount = \App\Models\Document::where('school_id', $school->id)->count();

        return view('admin.schools.show', compact('school', 'documentCount'));
    }

    public function edit(School $school)
    {
        $secretaria = $school->users()->whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
        $availableModules = School::availableModules();
        $settings = SchoolSetting::getAllForSchool($school->id);
        $subjects = \App\Models\Subject::withoutGlobalScope(\App\Scopes\SchoolScope::class)
            ->where('school_id', $school->id)
            ->orderBy('ordem')
            ->get();
        return view('admin.schools.edit', compact('school', 'secretaria', 'availableModules', 'settings', 'subjects'));
    }

    public function updateTerminologias(Request $request, School $school)
    {
        $terms = [
            'aluno', 'alunos', 'turma', 'turmas',
            'laudo', 'laudos', 'professor', 'professores',
            'coordenador', 'orientador', 'documento', 'documentos',
            'publico_alvo', 'nao_publico_alvo',
        ];

        foreach ($terms as $term) {
            $value = $request->input("term_{$term}", '');
            if ($value !== '') {
                SchoolSetting::setValue($school->id, "term_{$term}", trim($value));
            } else {
                SchoolSetting::where('school_id', $school->id)->where('key', "term_{$term}")->delete();
            }
        }

        return redirect()->route('admin.schools.edit', $school)
            ->with('success', 'Terminologias atualizadas.');
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'plan'              => 'required|in:free,pro,enterprise',
            'plan_status'       => 'required|in:active,inactive,suspended',
            'plan_expires_at'   => 'nullable|date',
            'max_students'      => 'required|integer|min:1',
            'is_active'         => 'boolean',
            'notes'             => 'nullable|string',
            'logo'              => 'nullable|file|mimes:svg,png,jpg,jpeg|max:2048',
            'theme_color'       => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'modules'           => 'nullable|array',
            'modules.*'         => 'string',
            'secretaria_name'   => 'nullable|string|max:255',
            'secretaria_email'  => 'nullable|email|unique:users,email,' . ($request->input('secretaria_id') ?: 'NULL'),
        ]);

        if ($request->hasFile('logo')) {
            if ($school->logo) {
                \Storage::disk('public')->delete($school->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            unset($data['logo']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['modules'] = !empty($data['modules']) ? array_values($data['modules']) : [];
        $school->update(\Arr::except($data, ['secretaria_name', 'secretaria_email']));

        // Atualiza usuário secretaria se informado
        if ($request->filled('secretaria_id') && $request->filled('secretaria_name')) {
            $secretaria = User::where('id', $request->secretaria_id)
                ->where('school_id', $school->id)
                ->first();

            if ($secretaria) {
                $secretaria->update(array_filter([
                    'name'  => $data['secretaria_name'],
                    'email' => $data['secretaria_email'] ?? null,
                ]));
            }
        }

        return redirect()->route('admin.schools.show', $school)
            ->with('success', 'Escola atualizada.');
    }

    public function destroy(School $school, Request $request)
{
    // Dupla verificação: nome da escola
    if ($request->input('confirmation') !== $school->name) {
        return back()->with('error', 'Nome da escola não confere. Exclusão cancelada.');
    }

    // Cascade manual
    $schoolId = $school->id;

    \DB::transaction(function () use ($school, $schoolId) {
        \App\Models\Document::where('school_id', $schoolId)->delete();
        \App\Models\Observation::where('school_id', $schoolId)->delete();

        // Laudos — apaga arquivos do storage também
        $laudos = \App\Models\Laudo::where('school_id', $schoolId)->get();
        foreach ($laudos as $laudo) {
            \Storage::disk('public')->delete($laudo->arquivo);
        }
        \App\Models\Laudo::where('school_id', $schoolId)->delete();

        // Pivôs
        \DB::table('school_class_student')
            ->whereIn('school_class_id', \App\Models\SchoolClass::where('school_id', $schoolId)->pluck('id'))
            ->delete();
        \DB::table('school_class_user')
            ->whereIn('school_class_id', \App\Models\SchoolClass::where('school_id', $schoolId)->pluck('id'))
            ->delete();
        \DB::table('student_user')
            ->whereIn('student_id', \App\Models\Student::where('school_id', $schoolId)->pluck('id'))
            ->delete();

        \App\Models\SchoolClass::where('school_id', $schoolId)->delete();
        \App\Models\Student::where('school_id', $schoolId)->delete();

        // Usuários — remove roles/permissions antes
        $users = \App\Models\User::where('school_id', $schoolId)->get();
        foreach ($users as $user) {
            $user->roles()->detach();
            $user->delete();
        }

        // Logo do storage
        if ($school->logo) {
            \Storage::disk('public')->delete($school->logo);
        }

        $school->delete();
    });

    return redirect()->route('admin.schools.index')
        ->with('success', 'Escola e todos os dados removidos com sucesso.');
}

    public function resetPassword(School $school, User $user)
    {
        $newPassword = str()->random(10);
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Nova senha de {$user->name}: {$newPassword}");
    }
}