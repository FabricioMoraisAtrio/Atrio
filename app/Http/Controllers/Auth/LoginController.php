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
            return redirect()->route($roleDashboards[$role] ?? 'login');
        }

        // Tenta guard admin (superadmin)
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Credenciais inválidas.'])
            ->onlyInput('email');
    }
}