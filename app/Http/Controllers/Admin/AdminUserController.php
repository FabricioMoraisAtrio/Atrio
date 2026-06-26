<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = AdminUser::orderBy('name')->get();

        return view('admin.administradores.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admin_users,email',
            'password' => 'required|min:6',
        ]);

        AdminUser::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Administrador criado.');
    }

    public function update(Request $request, AdminUser $administrador)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('admin_users', 'email')->ignore($administrador->id)],
            'password' => 'nullable|min:6',
        ]);

        $administrador->name  = $data['name'];
        $administrador->email = $data['email'];
        if (! empty($data['password'])) {
            $administrador->password = Hash::make($data['password']);
        }
        $administrador->save();

        return back()->with('success', 'Administrador atualizado.');
    }

    public function destroy(AdminUser $administrador)
    {
        if ($administrador->id === auth('admin')->id()) {
            return back()->with('error', 'Você não pode remover a si mesmo.');
        }
        if (AdminUser::count() <= 1) {
            return back()->with('error', 'É necessário ao menos um administrador.');
        }

        $administrador->delete();

        return back()->with('success', 'Administrador removido.');
    }
}
