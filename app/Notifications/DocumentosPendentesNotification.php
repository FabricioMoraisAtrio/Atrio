<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentosPendentesNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $pendentes) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Átrio — Documentos pendentes para hoje')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Os seguintes alunos possuem documentos pendentes de preenchimento:');

        foreach ($this->pendentes as $item) {
            $tipos = implode(', ', array_map('strtoupper', $item['faltando']));
            $mail->line('• **' . $item['aluno']->name . '** — ' . $tipos);
        }

        $mail->action('Acessar o sistema', url('/secretaria/alunos'))
             ->line('Este é um resumo diário automático do Sistema Átrio.');

        return $mail;
    }
}