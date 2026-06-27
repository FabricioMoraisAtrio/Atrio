<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\FaturaVencendoNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class FaturasVencendo extends Command
{
    protected $signature = 'atrio:faturas-vencendo';

    protected $description = 'Notifica as escolas sobre faturas a vencer e vencidas (lembrete automático).';

    public function handle(): int
    {
        $hoje = Carbon::today();

        $abertas = Invoice::with('school')->where('status', 'aberto')->get();
        $enviadas = 0;

        foreach ($abertas as $invoice) {
            $dias = $hoje->diffInDays($invoice->due_date, false); // >0 a vencer, <0 vencida, 0 hoje

            // Cadência (evita spam diário): 5/2/0 dias antes; e vencidas em 3 dias ou múltiplos de 7
            $deveNotificar = in_array($dias, [5, 2, 0], true)
                || ($dias < 0 && (abs($dias) === 3 || abs($dias) % 7 === 0));

            if (! $deveNotificar) {
                continue;
            }

            $destinatarios = User::where('school_id', $invoice->school_id)->role('admin')->get();
            if ($destinatarios->isEmpty()) {
                $destinatarios = User::where('school_id', $invoice->school_id)->get();
            }
            if ($destinatarios->isEmpty()) {
                continue;
            }

            Notification::send($destinatarios, new FaturaVencendoNotification($invoice, $dias));
            $enviadas++;
        }

        $this->info("Lembretes enviados para {$enviadas} fatura(s).");

        return self::SUCCESS;
    }
}
