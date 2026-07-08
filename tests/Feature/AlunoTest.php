<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlunoTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();
        $this->loginComo($this->secretaria);
    }

    public function test_listar_alunos(): void
    {
        Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno Teste',
            'registration_number' => '001',
            'birth_date'          => '2010-01-01',
        ]);

        $response = $this->get(route('secretaria.alunos.index'));

        $response->assertStatus(200);
        $response->assertSee('Aluno Teste');
    }

    public function test_listar_alunos_com_data_de_nascimento_nula(): void
    {
        // Alunos importados por CSV podem ter birth_date nula — a listagem não pode quebrar.
        Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno Sem Data',
            'registration_number' => '010',
            'birth_date'          => null,
        ]);

        $response = $this->get(route('secretaria.alunos.index'));

        $response->assertStatus(200);
        $response->assertSee('Aluno Sem Data');
    }

    public function test_criar_aluno(): void
    {
        $response = $this->post(route('secretaria.alunos.store'), [
            'name'                => 'Novo Aluno',
            'registration_number' => '002',
            'birth_date'          => '2010-05-10',
            'is_atypical'         => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', ['name' => 'Novo Aluno']);
    }

    public function test_matricula_duplicada_retorna_erro(): void
    {
        Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno 1',
            'registration_number' => '999',
            'birth_date'          => '2010-01-01',
        ]);

        $response = $this->post(route('secretaria.alunos.store'), [
            'name'                => 'Aluno 2',
            'registration_number' => '999',
            'birth_date'          => '2010-01-01',
        ]);

        $response->assertSessionHasErrors('registration_number');
    }

    public function test_editar_aluno(): void
    {
        $aluno = Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno Original',
            'registration_number' => '003',
            'birth_date'          => '2010-01-01',
        ]);

        $response = $this->put(route('secretaria.alunos.update', $aluno), [
            'name'                => 'Aluno Atualizado',
            'registration_number' => '003',
            'birth_date'          => '2010-01-01',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', ['name' => 'Aluno Atualizado']);
    }

    public function test_remover_aluno(): void
    {
        $aluno = Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno Removido',
            'registration_number' => '004',
            'birth_date'          => '2010-01-01',
        ]);

        $response = $this->delete(route('secretaria.alunos.destroy', $aluno));

        $response->assertRedirect();
        $this->assertDatabaseMissing('students', ['id' => $aluno->id]);
    }
}