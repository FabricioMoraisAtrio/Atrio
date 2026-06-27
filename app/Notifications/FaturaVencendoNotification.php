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

        $msg = (new MailMessage)->greeting('Olá, ' . $notifiable->name . '!');

        if ($vencida) {
            $msg->subject('Átrio — Fatura vencida')
                ->line("A fatura da escola **{$this->invoice->school?->name}** está **vencida** desde {$venc} (" . abs($this->dias) . ' dia(s)).')
                ->line("Valor: **{$valor}**.")
                ->line('Regularize para manter o acesso ao sistema.');
        } else {
            $quando = $this->dias === 0 ? 'vence **hoje**' : "vence em **{$this->dias} dia(s)**";
            $msg->subject('Átrio — Fatura ' . ($this->dias === 0 ? 'vence hoje' : "vence em {$this->dias} dia(s)"))
                ->line("A fatura da escola **{$this->invoice->school?->name}** {$quando} ({$venc}).")
                ->line("Valor: **{$valor}**.");
        }

        return $msg->line('Em caso de dúvidas, fale com a administração do Átrio.');
    }
}
