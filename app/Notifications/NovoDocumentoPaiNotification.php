<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NovoDocumentoPaiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Document $document) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $tipo = strtoupper(str_replace('_', ' ', $this->document->type));

        return (new MailMessage)
            ->subject('Átrio — Novo documento disponível para ' . $this->document->student->name)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Um novo documento foi criado para o seu filho **' . $this->document->student->name . '**.')
            ->line('**Tipo:** ' . $tipo)
            ->line('**Criado por:** ' . $this->document->author->name)
            ->line('**Data:** ' . $this->document->created_at->format('d/m/Y'))
            ->action('Ver documento', url('/responsavel/dashboard'))
            ->line('Sistema Átrio — Portal de Gestão Inclusiva.');
    }
}