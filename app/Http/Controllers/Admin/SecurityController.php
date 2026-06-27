<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Support\Totp;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /** Página de segurança do admin logado (status do 2FA / ativação). */
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        if ($admin->hasTwoFactor()) {
            return view('admin.seguranca', ['admin' => $admin]);
        }

        // Segredo pendente fica na sessão até a confirmação
        $secret = $request->session()->get('2fa:setup_secret') ?: Totp::secret();
        $request->session()->put('2fa:setup_secret', $secret);

        return view('admin.seguranca', [
            'admin'  => $admin,
            'secret' => $secret,
            'uri'    => Totp::uri($secret, $admin->email, config('app.name', 'Átrio')),
        ]);
    }

    /** Confirma o código e ativa o 2FA. */
    public function enable(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $admin  = auth('admin')->user();
        $secret = $request->session()->get('2fa:setup_secret');

        if (! $secret || ! Totp::verify($secret, $request->input('code'))) {
            return back()->withErrors(['code' => 'Código inválido. Verifique o app e tente de novo.']);
        }

        $admin->two_factor_secret = $secret;
        $admin->two_factor_confirmed_at = now();
        $admin->save();

        $request->session()->forget('2fa:setup_secret');
        AdminLog::record('2fa_ativado', 'Ativou a verificação em duas etapas');

        return redirect()->route('admin.security')->with('success', 'Verificação em duas etapas ativada.');
    }

    /** Desativa o 2FA (exige um código válido). */
    public function disable(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $admin = auth('admin')->user();

        if (! $admin->hasTwoFactor() || ! Totp::verify($admin->two_factor_secret, $request->input('code'))) {
            return back()->withErrors(['code' => 'Código inválido.']);
        }

        $admin->two_factor_secret = null;
        $admin->two_factor_confirmed_at = null;
        $admin->save();

        AdminLog::record('2fa_desativado', 'Desativou a verificação em duas etapas');

        return redirect()->route('admin.security')->with('success', 'Verificação em duas etapas desativada.');
    }
}
