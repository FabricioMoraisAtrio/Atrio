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
        $itens = [];
        foreach ($this->pendentes as $item) {
            $tipos = implode(', ', array_map('strtoupper', $item['faltando']));
            $itens[] = '**' . $item['aluno']->name . '** — ' . $tipos;
        }

        return (new MailMessage)
            ->subject('Átrio — Documentos pendentes para hoje')
            ->view('emails.notificacao', [
                'titulo'     => 'Documentos pendentes',
                'saudacao'   => 'Olá, ' . $notifiable->name . '!',
                'paragrafos' => ['Os seguintes alunos possuem documentos pendentes de preenchimento:'],
                'itens'      => $itens,
                'acaoTexto'  => 'Acessar o sistema',
                'acaoUrl'    => route('secretaria.alunos.index'),
                'rodape'     => 'Resumo diário automático do Sistema Átrio.',
            ]);
    }
}