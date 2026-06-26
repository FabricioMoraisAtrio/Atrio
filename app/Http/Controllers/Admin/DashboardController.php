<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\School;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $now      = Carbon::now();
        $today    = $now->toDateString();
        $mesAbrev = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        // ── Financeiro ──
        $recebidoMes = (float) Invoice::where('status', 'pago')
            ->whereBetween('paid_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->sum('amount');

        $vencido = (float) Invoice::where('status', 'aberto')
            ->whereDate('due_date', '<', $today)->sum('amount');

        $emAberto = (float) Invoice::where('status', 'aberto')
            ->whereDate('due_date', '>=', $today)->sum('amount');

        $mrr           = (float) School::where('is_active', true)->sum('monthly_fee');
        $activeSchools = School::where('is_active', true)->count();
        $totalSchools  = School::count();

        // Série de receita recebida (últimos 6 meses) para o gráfico
        $receitaSerie = [];
        for ($i = 5; $i >= 0; $i--) {
            $m   = $now->copy()->subMonths($i);
            $val = (float) Invoice::where('status', 'pago')
                ->whereYear('paid_at', $m->year)->whereMonth('paid_at', $m->month)->sum('amount');
            $receitaSerie[] = ['label' => $mesAbrev[$m->month - 1], 'value' => $val];
        }

        // ── Vencimentos ──
        $faturasAtrasadas = Invoice::with('school')->where('status', 'aberto')
            ->whereDate('due_date', '<', $today)->orderBy('due_date')->take(8)->get();

        $faturasAVencer = Invoice::with('school')->where('status', 'aberto')
            ->whereBetween('due_date', [$today, $now->copy()->addDays(15)->toDateString()])
            ->orderBy('due_date')->take(8)->get();

        $planosVencendo = School::whereNotNull('plan_expires_at')
            ->whereBetween('plan_expires_at', [$today, $now->copy()->addDays(30)->toDateString()])
            ->orderBy('plan_expires_at')->take(6)->get();

        $novasEscolas = School::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'recebidoMes', 'vencido', 'emAberto', 'mrr', 'activeSchools', 'totalSchools',
            'receitaSerie', 'faturasAtrasadas', 'faturasAVencer', 'planosVencendo', 'novasEscolas'
        ));
    }
}
