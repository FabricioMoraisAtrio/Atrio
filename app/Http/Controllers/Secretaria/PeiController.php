<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Professor\DocumentController as ProfessorPei;
use App\Models\Student;
use Illuminate\Http\Request;

/**
 * PEI unificado — uma única rota (/alunos/{aluno}/pei) para todos os perfis.
 * Mantém as DUAS telas existentes, escolhidas pela permissão:
 *  - quem tem `documentos.ver_todos` edita o PEI consolidado (todas as matérias);
 *  - o professor edita apenas a seção da própria matéria (derivada no servidor,
 *    com verificação de acesso à turma). Nenhuma tela foi reescrita.
 */
class PeiController extends Controller
{
    public function edit(Student $aluno)
    {
        if (auth()->user()->can('documentos.ver_todos')) {
            return app(PeiConsolidadoController::class)->edit($aluno);
        }

        return app(ProfessorPei::class)->editPei($aluno);
    }

    public function update(Request $request, Student $aluno)
    {
        if (auth()->user()->can('documentos.ver_todos')) {
            return app(PeiConsolidadoController::class)->update($request, $aluno);
        }

        return app(ProfessorPei::class)->updatePei($request, $aluno);
    }
}
