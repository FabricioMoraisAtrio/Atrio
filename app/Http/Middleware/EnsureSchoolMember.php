<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Permite acesso às rotas da secretaria para roles built-in
 * (admin, coordenador, orientador) e para roles customizados da escola
 * (prefixados com s{school_id}_).
 */
class EnsureSchoolMember
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Roles built-in do sistema (professor entra escopado às próprias turmas
        // pelas regras de permissão/escopo — portal unificado, sem rota separada).
        if ($user->hasAnyRole(['admin', 'coordenador', 'orientador', 'professor'])) {
            return $next($request);
        }

        // Roles customizados da escola: nome começa com s{school_id}_
        $schoolId = session('school_id');
        $hasCustomRole = $user->roles()
            ->where('name', 'like', "s{$schoolId}_%")
            ->exists();

        if ($hasCustomRole) {
            return $next($request);
        }

        abort(403, 'Acesso não autorizado.');
    }
}
