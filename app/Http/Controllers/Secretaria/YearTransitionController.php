<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Services\YearTransitionService;
use Illuminate\Http\Request;

class YearTransitionController extends Controller
{
    public function index(YearTransitionService $svc)
    {
        $current = $svc->currentYear();
        $target  = $current + 1;

        $turmas = SchoolClass::where('year', $current)
            ->with(['students' => fn ($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        $jaExiste = $svc->nextYearHasClasses($target);

        return view('secretaria.virada.index', compact('turmas', 'current', 'target', 'jaExiste'));
    }

    public function confirmar(Request $request, YearTransitionService $svc)
    {
        $current = $svc->currentYear();
        $target  = $current + 1;

        $data = $request->validate([
            'dest'       => 'array',
            'dest.*'     => 'nullable|string|max:255',
            'status'     => 'array',
            'status.*'   => 'in:promovido,retido,saiu',
            'confirma'   => 'accepted',
        ]);

        $resumo = $svc->prepare($target, $data['dest'] ?? [], $data['status'] ?? []);

        \App\Models\DocumentAccessLog::record('virada_ano', [
            'student_name' => "Preparou o ano {$target}: {$resumo['classes_created']} turma(s), {$resumo['students_enrolled']} matrícula(s)",
        ]);

        return redirect()
            ->route('secretaria.turmas.index')
            ->with('success', "Ano {$target} preparado: {$resumo['classes_created']} turma(s) criada(s), {$resumo['students_enrolled']} matrícula(s), {$resumo['teachers_copied']} professor(es) copiado(s).");
    }
}
