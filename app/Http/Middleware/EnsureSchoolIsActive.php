<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSchoolIsActive
{
public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();
        $school = $user->school;

        if (! $school || ! $school->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Escola inativa. Entre em contato com o suporte.']);
        }

        if ($school->isExpired()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Plano vencido em ' . $school->plan_expires_at->format('d/m/Y') . '. Entre em contato com o suporte.']);
        }
    }

    return $next($request);
}
}