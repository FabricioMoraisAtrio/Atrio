<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolHasModule
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $schoolId = session('school_id');
        $school   = $schoolId ? School::find($schoolId) : null;

        if ($school && ! $school->hasModule($module)) {
            if ($request->expectsJson()) {
                abort(403, 'Módulo não disponível.');
            }

            return redirect()->route('secretaria.dashboard')
                ->with('error', 'Sua escola não tem acesso a este módulo.');
        }

        return $next($request);
    }
}
