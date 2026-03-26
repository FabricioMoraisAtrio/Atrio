<?php

namespace Tests\Unit;

use App\Models\Observation;
use App\Models\School;
use App\Notifications\DocumentosPendentesNotification;
use App\Notifications\ObservacaoCriticaNotification;
use App\Notifications\PlanoVencendoNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;

class NotificacaoTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();
    }

    public function test_notificacao_documentos_pendentes_usa_mail(): void
    {
        $notification = new DocumentosPendentesNotification([]);
        $this->assertContains('mail', $notification->via(new \stdClass()));
    }

    public function test_notificacao_observacao_critica_usa_mail(): void
    {
        $aluno = \App\Models\Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno',
            'registration_number' => '999',
            'birth_date'          => '2010-01-01',
        ]);

        $obs = Observation::create([
            'school_id'  => $this->escola->id,
            'student_id' => $aluno->id,
            'user_id'    => $this->secretaria->id,
            'content'    => 'Obs crítica',
            'urgency'    => 'critico',
            'category'   => 'comportamento',
        ]);

        $obs->load('student', 'user');

        $notification = new ObservacaoCriticaNotification($obs);
        $this->assertContains('mail', $notification->via(new \stdClass()));
    }

    public function test_notificacao_plano_vencendo_usa_mail(): void
    {
        $this->escola->update(['plan_expires_at' => now()->addDays(7)]);

        $notification = new PlanoVencendoNotification($this->escola, 7);
        $this->assertContains('mail', $notification->via(new \stdClass()));
    }
}