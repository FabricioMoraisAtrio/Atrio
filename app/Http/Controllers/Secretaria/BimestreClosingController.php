<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\BimestreClosing;
use App\Models\Student;
use App\Services\BimestreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimestreClosingController extends Controller
{
    /** Fecha um bimestre: congela o resultado (snapshot) e trava as avaliações. */
    public function store(Student $aluno, int $bimestre, BimestreService $bimestres)
    {
        abort_unless(in_array($bimestre, BimestreService::BIMESTRES, true), 404);

        $ano = (int) date('Y');

        if (BimestreClosing::where('student_id', $aluno->id)->where('year', $ano)->where('bimestre', $bimestre)->exists()) {
            return back()->with('success', 'Bimestre já estava fechado.');
        }

        // Bloqueia fechar antes do início do período configurado.
        $datas = $bimestres->datas(session('school_id'));
        if (! $bimestres->podeEncerrar($datas, $bimestre)) {
            return back()->withErrors(['bimestre' => 'Este bimestre ainda não começou; não é possível fechá-lo agora.']);
        }

        // Snapshot do resultado (% e nº de metas avaliadas) naquele bimestre.
        $grafico  = GoalProgressController::dadosEvolucao($aluno)['grafico'];
        $snapshot = $grafico[$bimestre] ?? ['avaliadas' => 0, 'percentual' => null];

        BimestreClosing::create([
            'school_id'  => session('school_id'),
            'student_id' => $aluno->id,
            'year'       => $ano,
            'bimestre'   => $bimestre,
            'snapshot'   => $snapshot,
            'closed_by'  => Auth::id(),
        ]);

        return redirect()->route('secretaria.alunos.linha-do-tempo', $aluno)
            ->with('success', "{$bimestre}º bimestre fechado.");
    }

    /** Reabre um bimestre fechado — restrito a admin/coordenador. */
    public function destroy(Student $aluno, int $bimestre)
    {
        abort_unless(Auth::user()->hasAnyRole(['admin', 'coordenador']), 403, 'Somente admin/coordenação pode reabrir um bimestre.');

        BimestreClosing::where('student_id', $aluno->id)
            ->where('year', (int) date('Y'))
            ->where('bimestre', $bimestre)
            ->delete();

        return redirect()->route('secretaria.alunos.linha-do-tempo', $aluno)
            ->with('success', "{$bimestre}º bimestre reaberto.");
    }
}
