<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminCan
{
    /**
     * Garante que o AdminUser logado tem acesso à rotina indicada.
     * Uso: ->middleware('admin.can:financeiro')
     */
    public function handle(Request $request, Closure $next, string $routine): Response
    {
        $admin = auth('admin')->user();

        if (! $admin || ! $admin->canAccess($routine)) {
            return redirect()
                ->route($admin ? $admin->homeRoute() : 'admin.dashboard')
                ->with('error', 'Você não tem acesso a essa rotina.');
        }

        return $next($request);
    }
}
