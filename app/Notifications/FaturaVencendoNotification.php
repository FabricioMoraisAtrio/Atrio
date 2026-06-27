<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FaturaVencendoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @param int $dias dias até o vencimento (negativo = vencida) */
    public function __construct(public Invoice $invoice, public int $dias) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $valor   = 'R$ ' . number_format((float) $this->invoice->amount, 2, ',', '.');
        $venc    = $this->invoice->due_date->format('d/m/Y');
        $vencida = $this->dias < 0;
        $escola  = $this->invoice->school?->name;

        if ($vencida) {
            $subject    = 'Átrio — Fatura vencida';
            $titulo     = 'Fatura vencida';
            $paragrafos = [
                "A fatura da escola **{$escola}** está **vencida** desde {$venc} (" . abs($this->dias) . ' dia(s)).',
                'Regularize para manter o acesso ao sistema.',
            ];
        } else {
            $quando     = $this->dias === 0 ? 'vence **hoje**' : "vence em **{$this->dias} dia(s)**";
            $subject    = 'Átrio — Fatura ' . ($this->dias === 0 ? 'vence hoje' : "vence em {$this->dias} dia(s)");
            $titulo     = 'Fatura a vencer';
            $paragrafos = ["A fatura da escola **{$escola}** {$quando} ({$venc})."];
        }

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.notificacao', [
                'titulo'     => $titulo,
                'topo'       => $vencida ? '#B42318' : '#B45309',
                'saudacao'   => 'Olá, ' . $notifiable->name . '!',
                'paragrafos' => $paragrafos,
                'dados'      => ['Valor' => $valor, 'Vencimento' => $venc],
                'rodape'     => 'Em caso de dúvidas, fale com a administração do Átrio.',
            ]);
    }
}
