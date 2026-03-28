<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('admin.login');
    }

    
public function store(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors(['email' => 'Credenciais inválidas.'])->onlyInput('email');
    }

    $request->session()->regenerate();
    
    return redirect()->route('admin.dashboard');
}

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login', ['perfil' => 'escola']);
    }
}