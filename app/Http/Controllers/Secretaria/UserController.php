<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\SchoolRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Student;
use App\Models\Subject;

class UserController extends Controller
{
    private function rolesPermitidos(): array
    {
        $base = ['professor', 'coordenador', 'orientador', 'admin'];

        // Inclui os spatie_roles de perfis customizados desta escola
        $custom = SchoolRole::where('school_id', session('school_id'))
            ->where('is_system', false)
            ->pluck('spatie_role')
            ->toArray();

        return array_merge($base, $custom);
    }

    /** Retorna todas as roles disponíveis para atribuição (built-in + custom) */
    private function rolesDisponiveis(): \Illuminate\Support\Collection
    {
        $schoolId = session('school_id');

        // Roles built-in (apenas os dois perfis padrão do sistema)
        $builtin = Role::whereIn('name', ['professor', 'admin'])
            ->get()
            ->map(fn($r) => (object)['spatie_role' => $r->name, 'name' => match($r->name) { 'admin' => 'Administrador', 'professor' => 'Professor', default => ucfirst($r->name) }, 'is_system' => true]);

        // Roles customizados da escola
        $custom = SchoolRole::where('school_id', $schoolId)
            ->where('is_system', false)
            ->get()
            ->map(fn($r) => (object)['spatie_role' => $r->spatie_role, 'name' => $r->name, 'is_system' => false]);

        return $builtin->merge($custom);
    }

    public function index()
    {
        $usuarios = User::with('roles', 'schoolClasses')
            ->where('school_id', session('school_id'))
            ->whereHas('roles', fn($q) => $q->whereIn('name', $this->rolesPermitidos()))
            ->latest()
            ->get();

        return view('secretaria.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $turmas   = SchoolClass::where('year', date('Y'))->orderBy('name')->get();
        $roles    = $this->rolesDisponiveis();
        $subjects = Subject::orderBy('ordem')->get();

        return view('secretaria.usuarios.create', compact('turmas', 'roles', 'subjects'));
    }

    public function store(Request $request)
    {
        $rolesValidos = implode(',', $this->rolesPermitidos());

        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'admin_password'     => 'required|min:6',
            'role'               => ['required', 'string', function ($attr, $value, $fail) {
                if (! Role::where('name', $value)->exists()) {
                    $fail('Perfil inválido.');
                }
            }],
            'school_class_ids'   => 'nullable|array',
            'school_class_ids.*' => 'exists:school_classes,id',
            'subject'            => 'nullable|exists:subjects,slug',
            'student_ids'        => 'nullable|array',
            'student_ids.*'      => 'exists:students,id',
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['admin_password']),
            'school_id' => session('school_id'),
            'is_active' => true,
        ]);

        $user->assignRole($data['role']);

        if ($data['role'] === 'professor' && ! empty($data['school_class_ids'])) {
            $turmas = [];
            foreach ($data['school_class_ids'] as $classId) {
                $turmas[$classId] = ['subject' => $data['subject'] ?? null];
            }
            $user->schoolClasses()->attach($turmas);
        }

        return redirect()->route('secretaria.usuarios.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $usuario)
    {
        $usuario->load('roles', 'schoolClasses');
        $turmas   = SchoolClass::where('year', date('Y'))->orderBy('name')->get();
        $subjects = Subject::orderBy('ordem')->get();

        return view('secretaria.usuarios.edit', compact('usuario', 'turmas', 'subjects'));
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email,' . $usuario->id,
            'password'           => 'nullable|min:6',
            'school_class_ids'   => 'nullable|array',
            'school_class_ids.*' => 'exists:school_classes,id',
            'subject'            => 'nullable|exists:subjects,slug',
            'student_ids'        => 'nullable|array',
            'student_ids.*'      => 'exists:students,id',
            'is_active'          => 'boolean',
        ]);

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $usuario->update($updateData);

        if ($usuario->hasRole('professor')) {
            $turmas = [];
            foreach ($data['school_class_ids'] ?? [] as $classId) {
                $turmas[$classId] = ['subject' => $data['subject'] ?? null];
            }
            $usuario->schoolClasses()->sync($turmas);
        }

        return redirect()->route('secretaria.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $usuario)
    {
        $usuario->schoolClasses()->detach();
        $usuario->delete();

        return redirect()->route('secretaria.usuarios.index')
            ->with('success', 'Usuário removido.');
    }
}