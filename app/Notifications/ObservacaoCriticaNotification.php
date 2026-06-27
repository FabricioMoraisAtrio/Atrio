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
            ->view('emails.notificacao', [
                'titulo'     => 'Observação crítica',
                'topo'       => '#B42318',
                'saudacao'   => 'Olá, ' . $notifiable->name . '!',
                'paragrafos' => ['Uma observação **crítica** foi registrada para o aluno **' . $this->observation->student->name . '**.'],
                'dados'      => [
                    'Registrado por' => $this->observation->user->name,
                    'Categoria'      => ucfirst($this->observation->category),
                    'Observação'     => $this->observation->content,
                ],
                'acaoTexto'  => 'Ver perfil do aluno',
                'acaoUrl'    => route('secretaria.alunos.show', $this->observation->student_id),
                'rodape'     => 'Sistema Átrio — Portal de Gestão Inclusiva.',
            ]);
    }
}