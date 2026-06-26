<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $now      = Carbon::now();
        $mesAbrev = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        // Totais gerais
        $totais = [
            'escolas'    => School::count(),
            'alunos'     => Student::withoutGlobalScopes()->count(),
            'documentos' => Document::withoutGlobalScopes()->count(),
            'usuarios'   => User::withoutGlobalScopes()->count(),
        ];

        // Por escola (engajamento)
        $porEscola = School::withCount([
            'students',
            'documents',
            'users',
        ])->orderByDesc('students_count')->get();

        // Crescimento: novas escolas e documentos por mês (últimos 6)
        $serieEscolas = [];
        $serieDocs    = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $serieEscolas[] = [
                'label' => $mesAbrev[$m->month - 1],
                'value' => School::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count(),
            ];
            $serieDocs[] = [
                'label' => $mesAbrev[$m->month - 1],
                'value' => Document::withoutGlobalScopes()->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count(),
            ];
        }

        return view('admin.relatorios.index', compact('totais', 'porEscola', 'serieEscolas', 'serieDocs'));
    }
}
