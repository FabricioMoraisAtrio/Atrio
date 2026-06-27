<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        // Tenta guard padrão (secretaria, professor, pai)
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();

            if (! $user->is_active) {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'Usuário inativo.'])->onlyInput('email');
            }

            $request->session()->regenerate();
            $request->session()->put('school_id', $user->school_id);

            $roleDashboards = [
                'admin'       => 'secretaria.dashboard',
                'coordenador' => 'secretaria.dashboard',
                'orientador'  => 'secretaria.dashboard',
                'professor'   => 'professor.dashboard',
            ];
            $role = $user->getRoleNames()->first();

            // Perfis customizados da escola (prefixo s{school_id}_) → dashboard secretaria
            if (!isset($roleDashboards[$role]) && $role && str_starts_with($role, 's')) {
                return redirect()->route('secretaria.dashboard');
            }

            return redirect()->route($roleDashboards[$role] ?? 'login');
        }

        // Tenta guard admin (superadmin)
        if (Auth::guard('admin')->validate($credentials)) {
            $admin = \App\Models\AdminUser::where('email', $credentials['email'])->first();

            // 2FA: segura o login e exige o código do app autenticador
            if ($admin->hasTwoFactor()) {
                $request->session()->put('2fa:admin_id', $admin->id);
                $request->session()->put('2fa:remember', $remember);
                return redirect()->route('admin.2fa');
            }

            Auth::guard('admin')->login($admin, $remember);
            $request->session()->regenerate();
            return redirect()->route($admin->homeRoute());
        }

        return back()
            ->withErrors(['email' => 'Credenciais inválidas.'])
            ->onlyInput('email');
    }
}