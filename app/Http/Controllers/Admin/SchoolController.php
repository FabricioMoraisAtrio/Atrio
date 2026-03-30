<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
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
        ]);

        $secretaria = User::create([
            'name'      => $data['admin_name'],
            'email'     => $data['admin_email'],
            'password'  => Hash::make($data['admin_password']),
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        $secretaria->assignRole('secretaria');

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
        $secretaria = $school->users()->whereHas('roles', fn($q) => $q->where('name', 'secretaria'))->first();
        return view('admin.schools.edit', compact('school', 'secretaria'));
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

    public function destroy(School $school)
    {
        if ($school->students()->exists() || $school->users()->exists() || $school->documents()->exists()) {
            return back()->with('error', 'Não é possível remover a escola pois ela possui usuários, alunos ou documentos cadastrados.');
        }

        $school->delete();
        return redirect()->route('admin.schools.index')
            ->with('success', 'Escola removida.');
    }

    public function resetPassword(School $school, User $user)
    {
        $newPassword = str()->random(10);
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Nova senha de {$user->name}: {$newPassword}");
    }
}