<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:admin_users,email',
            'password'    => 'required|min:6',
            'access_mode' => 'required|in:full,custom',
            'permissions' => 'nullable|array',
            'permissions.*' => ['string', Rule::in(array_keys(AdminUser::ROUTINES))],
        ]);

        $novo = AdminUser::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'permissions' => $this->permissionsFromRequest($request),
        ]);
        AdminLog::record('admin_criado', "Administrador {$novo->name} ({$novo->email}) criado");

        return back()->with('success', 'Administrador criado.');
    }

    public function update(Request $request, AdminUser $administrador)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', Rule::unique('admin_users', 'email')->ignore($administrador->id)],
            'password'    => 'nullable|min:6',
            'access_mode' => 'required|in:full,custom',
            'permissions' => 'nullable|array',
            'permissions.*' => ['string', Rule::in(array_keys(AdminUser::ROUTINES))],
        ]);

        $permissions = $this->permissionsFromRequest($request);

        // Trava anti-lockout: não pode remover o próprio acesso a "Administradores".
        if ($administrador->id === auth('admin')->id()
            && $permissions !== null && ! in_array('administradores', $permissions, true)) {
            return back()->with('error', 'Você não pode remover o seu próprio acesso a Administradores.');
        }

        $administrador->name        = $data['name'];
        $administrador->email       = $data['email'];
        $administrador->permissions = $permissions;
        if (! empty($data['password'])) {
            $administrador->password = Hash::make($data['password']);
        }
        $administrador->save();
        AdminLog::record('admin_editado', "Administrador {$administrador->name} editado" . ($permissions === null ? ' (acesso total)' : ' (acesso restrito)'));

        return back()->with('success', 'Administrador atualizado.');
    }

    /** null = acesso total; array = rotinas marcadas (sempre inclui dashboard). */
    private function permissionsFromRequest(Request $request): ?array
    {
        if ($request->input('access_mode') === 'full') {
            return null;
        }

        $perms = array_values(array_intersect(
            array_keys(AdminUser::ROUTINES),
            (array) $request->input('permissions', [])
        ));

        // Garante ao menos o Dashboard como ponto de entrada.
        if (! in_array('dashboard', $perms, true)) {
            array_unshift($perms, 'dashboard');
        }

        return $perms;
    }

    public function destroy(AdminUser $administrador)
    {
        if ($administrador->id === auth('admin')->id()) {
            return back()->with('error', 'Você não pode remover a si mesmo.');
        }
        if (AdminUser::count() <= 1) {
            return back()->with('error', 'É necessário ao menos um administrador.');
        }

        AdminLog::record('admin_removido', "Administrador {$administrador->name} removido");
        $administrador->delete();

        return back()->with('success', 'Administrador removido.');
    }
}
