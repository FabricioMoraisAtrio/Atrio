<?php

namespace App\Notifications;

use App\Models\Observation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ObservacaoCriticaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Observation $observation) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Átrio — Observação crítica registrada')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Uma observação **crítica** foi registrada para o aluno **' . $this->observation->student->name . '**.')
            ->line('**Registrado por:** ' . $this->observation->user->name)
            ->line('**Categoria:** ' . ucfirst($this->observation->category))
            ->line('**Observação:** ' . $this->observation->content)
            ->action('Ver perfil do aluno', url('/secretaria/alunos/' . $this->observation->student_id))
            ->line('Sistema Átrio — Portal de Gestão Inclusiva.');
    }
}