<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorChallengeController extends Controller
{
    /** Tela do desafio 2FA (entre senha correta e acesso liberado). */
    public function show(Request $request)
    {
        if (! $request->session()->has('2fa:admin_id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.2fa-challenge');
    }

    /** Confere o código e completa o login. */
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $id = $request->session()->get('2fa:admin_id');
        $admin = $id ? AdminUser::find($id) : null;

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        if (! Totp::verify($admin->two_factor_secret, $request->input('code'))) {
            return back()->withErrors(['code' => 'Código inválido. Tente novamente.']);
        }

        $remember = (bool) $request->session()->pull('2fa:remember', false);
        $request->session()->forget('2fa:admin_id');

        Auth::guard('admin')->login($admin, $remember);
        $request->session()->regenerate();

        return redirect()->route($admin->homeRoute());
    }
}
