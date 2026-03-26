<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Student;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentoTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected Student $aluno;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();
        $this->loginComo($this->secretaria);

        $this->aluno = Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Aluno Doc',
            'registration_number' => '100',
            'birth_date'          => '2010-01-01',
            'is_atypical'         => true,
        ]);
    }

    public function test_criar_estudo_de_caso(): void
    {
        $response = $this->post(route('secretaria.alunos.documentos.store', $this->aluno), [
            'type'            => 'estudo_caso',
            'historico'       => 'Histórico do aluno',
            'barreiras'       => 'Barreiras identificadas',
            'potencialidades' => 'Pontos fortes',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', [
            'student_id' => $this->aluno->id,
            'type'       => 'estudo_caso',
        ]);
    }

    public function test_bloqueia_pei_sem_estudo_de_caso(): void
    {
        $response = $this->post(route('secretaria.alunos.documentos.store', $this->aluno), [
            'type'      => 'pei',
            'objetivos' => 'Objetivos do PEI',
        ]);

        $response->assertSessionHasErrors('documento');
        $this->assertDatabaseMissing('documents', [
            'student_id' => $this->aluno->id,
            'type'       => 'pei',
        ]);
    }

    public function test_permite_pei_com_estudo_de_caso(): void
    {
        Document::create([
            'school_id'  => $this->escola->id,
            'student_id' => $this->aluno->id,
            'author_id'  => $this->secretaria->id,
            'type'       => 'estudo_caso',
            'year'       => date('Y'),
            'status'     => 'draft',
            'content'    => ['historico' => 'ok'],
        ]);

        $response = $this->post(route('secretaria.alunos.documentos.store', $this->aluno), [
            'type'      => 'pei',
            'objetivos' => 'Objetivos do PEI',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', [
            'student_id' => $this->aluno->id,
            'type'       => 'pei',
        ]);
    }

        public function test_bloqueia_documento_duplicado_no_ano(): void
        {
            Document::create([
                'school_id'  => $this->escola->id,
                'student_id' => $this->aluno->id,
                'author_id'  => $this->secretaria->id,
                'type'       => 'estudo_caso',
                'year'       => date('Y'),
                'status'     => 'draft',
                'content'    => ['historico' => 'ok'],
            ]);

            $response = $this->post(route('secretaria.alunos.documentos.store', $this->aluno), [
                'type'      => 'estudo_caso',
                'historico' => 'Tentativa duplicada',
            ]);

            // Não deve criar um segundo documento
            $this->assertEquals(1, Document::where([
                'student_id' => $this->aluno->id,
                'type'       => 'estudo_caso',
                'year'       => date('Y'),
            ])->count());
        }

    public function test_exportar_pdf(): void
    {
        $doc = Document::create([
            'school_id'  => $this->escola->id,
            'student_id' => $this->aluno->id,
            'author_id'  => $this->secretaria->id,
            'type'       => 'estudo_caso',
            'year'       => date('Y'),
            'status'     => 'draft',
            'content'    => ['historico' => 'ok', 'barreiras' => 'ok'],
        ]);

        $response = $this->get(route('secretaria.documentos.pdf', $doc));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_exportar_word(): void
    {
        $doc = Document::create([
            'school_id'  => $this->escola->id,
            'student_id' => $this->aluno->id,
            'author_id'  => $this->secretaria->id,
            'type'       => 'estudo_caso',
            'year'       => date('Y'),
            'status'     => 'draft',
            'content'    => ['historico' => 'ok', 'barreiras' => 'ok'],
        ]);

        $response = $this->get(route('secretaria.documentos.word', $doc));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    }
}