<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Notifications\DocumentosPendentesNotification;
use App\Notifications\PlanoVencendoNotification;
use Illuminate\Console\Command;

class EnviarNotificacoesDiarias extends Command
{
    protected $signature   = 'atrio:notificacoes-diarias';
    protected $description = 'Envia o resumo diário de notificações';

    public function handle(): void
    {
        $this->notificarDocumentosPendentes();
        $this->notificarPlanoVencendo();
        $this->info('Notificações enviadas com sucesso.');
    }

private function notificarDocumentosPendentes(): void
{
    $escolas = School::where('is_active', true)->get();
    $delay = 0;

    foreach ($escolas as $escola) {
        $alunosAtipicos = Student::where('school_id', $escola->id)
            ->where('is_atypical', true)
            ->with(['documents' => fn($q) => $q->where('year', date('Y'))->select('id','student_id','type')])
            ->get();

        $pendentes = $alunosAtipicos->filter(function ($aluno) {
            $criados = $aluno->documents->pluck('type')->toArray();
            return count(array_diff(['estudo_caso','pei','paee'], $criados)) > 0;
        })->map(function ($aluno) {
            $criados = $aluno->documents->pluck('type')->toArray();
            return [
                'aluno'    => $aluno,
                'faltando' => array_values(array_diff(['estudo_caso','pei','paee'], $criados)),
            ];
        });

        if ($pendentes->isEmpty()) continue;

        $usuarios = User::where('school_id', $escola->id)
            ->where('notify_document_pending', true)
            ->whereHas('roles', fn($q) => $q->whereIn('name', ['secretaria', 'professor']))
            ->get();

        foreach ($usuarios as $usuario) {
            $usuario->notify(
                (new DocumentosPendentesNotification($pendentes->values()->toArray()))
                    ->delay(now()->addSeconds($delay))
            );
            $delay += 10;
        }
    }
}

private function notificarPlanoVencendo(): void
{
    $escolas = School::whereNotNull('plan_expires_at')
        ->where('is_active', true)
        ->get();

    $delay = 0;

    foreach ($escolas as $escola) {
        $diasRestantes = now()->diffInDays($escola->plan_expires_at, false);

        if (! in_array($diasRestantes, [30, 15, 7, 3, 1])) continue;

        $secretarias = User::where('school_id', $escola->id)
            ->where('notify_plan_expiring', true)
            ->whereHas('roles', fn($q) => $q->where('name', 'secretaria'))
            ->get();

        foreach ($secretarias as $usuario) {
            $usuario->notify(
                (new PlanoVencendoNotification($escola, $diasRestantes))
                    ->delay(now()->addSeconds($delay))
            );
            $delay += 10;
        }
    }
}
}
