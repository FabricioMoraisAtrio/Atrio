<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Student;
use App\Models\Subject;
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

    public function test_bloqueia_paee_sem_estudo_de_caso(): void
    {
        // Estudo de Caso é pré-requisito do PAEE.
        $response = $this->post(route('secretaria.alunos.documentos.store', $this->aluno), [
            'type' => 'paee',
        ]);

        $response->assertSessionHasErrors('documento');
        $this->assertDatabaseMissing('documents', [
            'student_id' => $this->aluno->id,
            'type'       => 'paee',
        ]);
    }

    public function test_permite_paee_com_estudo_de_caso(): void
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
            'type'      => 'paee',
            'objetivos' => 'Objetivos do PAEE',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', [
            'student_id' => $this->aluno->id,
            'type'       => 'paee',
        ]);
    }

    public function test_estudo_de_caso_cria_pei_automaticamente(): void
    {
        // Ao criar o Estudo de Caso, o PEI (Document tipo pei) é criado vazio.
        $this->post(route('secretaria.alunos.documentos.store', $this->aluno), [
            'type'            => 'estudo_caso',
            'historico'       => 'Histórico',
            'barreiras'       => 'Barreiras',
            'potencialidades' => 'Potencialidades',
        ])->assertRedirect();

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

    public function test_exporta_pdf_do_pei_com_todas_as_materias(): void
    {
        // Matéria da escola sem metas preenchidas → deve renderizar sem erro
        // (o PDF do PEI lista todas as disciplinas, marcando as vazias).
        Subject::create([
            'school_id' => $this->escola->id,
            'name'      => 'Matemática',
            'slug'      => 'matematica',
            'tipo'      => 'disciplina',
            'ordem'     => 1,
        ]);

        $pei = Document::create([
            'school_id'  => $this->escola->id,
            'student_id' => $this->aluno->id,
            'author_id'  => $this->secretaria->id,
            'type'       => 'pei_consolidado',
            'year'       => date('Y'),
            'status'     => 'published',
            'content'    => [],
        ]);

        $response = $this->get(route('secretaria.documentos.pdf', $pei));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}