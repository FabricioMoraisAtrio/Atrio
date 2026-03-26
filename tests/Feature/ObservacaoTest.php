<?php

namespace Tests\Feature;

use App\Models\Observation;
use App\Models\Student;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObservacaoTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected Student $aluno;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();

        $this->aluno = Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno Obs',
            'registration_number' => '200',
            'birth_date'          => '2010-01-01',
        ]);
    }

    public function test_secretaria_registra_observacao(): void
    {
        $this->loginComo($this->secretaria);

        $response = $this->post(route('secretaria.alunos.observacoes.store', $this->aluno), [
            'content'  => 'Observação de teste',
            'urgency'  => 'normal',
            'category' => 'comportamento',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('observations', [
            'student_id' => $this->aluno->id,
            'content'    => 'Observação de teste',
        ]);
    }

    public function test_professor_remove_apenas_propria_observacao(): void
    {
        $this->loginComo($this->professor);

        $obs = Observation::create([
            'school_id'  => $this->escola->id,
            'student_id' => $this->aluno->id,
            'user_id'    => $this->secretaria->id,
            'content'    => 'Obs da secretaria',
            'urgency'    => 'normal',
            'category'   => 'comportamento',
        ]);

        $response = $this->delete(route('professor.observacoes.destroy', $obs));

        $response->assertStatus(403);
        $this->assertDatabaseHas('observations', ['id' => $obs->id]);
    }

    public function test_secretaria_remove_qualquer_observacao(): void
    {
        $this->loginComo($this->secretaria);

        $obs = Observation::create([
            'school_id'  => $this->escola->id,
            'student_id' => $this->aluno->id,
            'user_id'    => $this->professor->id,
            'content'    => 'Obs do professor',
            'urgency'    => 'normal',
            'category'   => 'comportamento',
        ]);

        $response = $this->delete(route('secretaria.observacoes.destroy', $obs));

        $response->assertRedirect();
        $this->assertDatabaseMissing('observations', ['id' => $obs->id]);
    }
}