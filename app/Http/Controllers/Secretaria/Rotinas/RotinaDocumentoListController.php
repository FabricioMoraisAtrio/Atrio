<?php

namespace App\Http\Controllers\Secretaria\Rotinas;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class RotinaDocumentoListController extends Controller
{
    private array $config = [
        'estudo_caso' => [
            'titulo'        => 'Estudo de Caso',
            'tipo'          => 'estudo_caso',
            'so_publico'    => false,
            'multiplos'     => false,
            'view'          => 'secretaria.rotinas.documentos.estudo-caso',
        ],
        'paee' => [
            'titulo'        => 'PAEE',
            'tipo'          => 'paee',
            'so_publico'    => true,
            'multiplos'     => false,
            'view'          => 'secretaria.rotinas.documentos.paee',
        ],
        'pei' => [
            'titulo'        => 'PEI',
            'tipo'          => 'pei',
            'so_publico'    => true,
            'multiplos'     => false,
            'view'          => 'secretaria.rotinas.documentos.pei',
        ],
        'atendimento' => [
            'titulo'        => 'Atendimentos',
            'tipo'          => 'atendimento',
            'so_publico'    => false,
            'multiplos'     => true,
            'view'          => 'secretaria.rotinas.documentos.atendimentos',
        ],
    ];

    public function __invoke(Request $request, string $tipo)
    {
        $cfg    = $this->config[$tipo];
        $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get(['id', 'name', 'shift']);

        $types = $cfg['tipo'] === 'pei'
            ? [$cfg['tipo'], 'pei_consolidado']
            : [$cfg['tipo']];

        $query = Student::with([
            'schoolClasses' => fn($q) => $q->where('year', date('Y'))->select('school_classes.id', 'name', 'shift'),
            'documents'     => fn($q) => $q->where('year', date('Y'))
                                           ->whereIn('type', $types)
                                           ->with('author:id,name')
                                           ->select('id', 'student_id', 'author_id', 'type', 'status', 'updated_at'),
            'laudos',
        ]);

        if ($cfg['so_publico']) {
            $query->where('is_atypical', true);
        }

        if ($request->filled('turma')) {
            $query->whereHas('schoolClasses', fn($q) => $q->where('school_classes.id', $request->turma));
        }

        if ($request->filled('publico')) {
            $query->where('is_atypical', $request->publico === 'sim');
        }

        $alunos = $query->orderBy('name')->get();

        return view($cfg['view'], [
            'alunos' => $alunos,
            'turmas' => $turmas,
            'cfg'    => $cfg,
        ]);
    }
}
