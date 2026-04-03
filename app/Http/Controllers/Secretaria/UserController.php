<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
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
        return ['professor', 'pai', 'coordenador', 'orientador'];
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
        $roles    = Role::whereIn('name', $this->rolesPermitidos())->get();
        $alunos   = Student::orderBy('name')->get();
        $subjects = Subject::orderBy('ordem')->get();

        return view('secretaria.usuarios.create', compact('turmas', 'roles', 'alunos', 'subjects'));
    }

    public function store(Request $request)
    {
        $rolesValidos = implode(',', $this->rolesPermitidos());

        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'admin_password'     => 'required|min:6',
            'role'               => 'required|in:' . $rolesValidos,
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

        if ($data['role'] === 'pai' && ! empty($data['student_ids'])) {
            $user->children()->attach($data['student_ids']);
        }

        return redirect()->route('secretaria.usuarios.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $usuario)
    {
        $usuario->load('roles', 'schoolClasses', 'children');
        $turmas   = SchoolClass::where('year', date('Y'))->orderBy('name')->get();
        $alunos   = Student::orderBy('name')->get();
        $subjects = Subject::orderBy('ordem')->get();

        return view('secretaria.usuarios.edit', compact('usuario', 'turmas', 'alunos', 'subjects'));
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

        if ($usuario->hasRole('pai')) {
            $usuario->children()->sync($data['student_ids'] ?? []);
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