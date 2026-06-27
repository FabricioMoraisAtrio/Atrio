<?php

namespace App\Notifications;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PlanoVencendoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public School $school, public int $diasRestantes) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Átrio — Plano vencendo em ' . $this->diasRestantes . ' dias')
            ->view('emails.notificacao', [
                'titulo'     => 'Plano vencendo',
                'topo'       => '#B45309',
                'saudacao'   => 'Olá, ' . $notifiable->name . '!',
                'paragrafos' => [
                    'O plano da escola **' . $this->school->name . '** vence em **' . $this->diasRestantes . ' dias** (' . $this->school->plan_expires_at->format('d/m/Y') . ').',
                    'Renove agora para não perder o acesso ao sistema.',
                ],
                'acaoTexto'  => 'Falar com suporte',
                'acaoUrl'    => url('/'),
                'rodape'     => 'Sistema Átrio — Portal de Gestão Inclusiva.',
            ]);
    }
}