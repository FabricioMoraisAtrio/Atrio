<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;

/**
 * Fundação da unificação de rotas (Fase 1/2): o escopo por permissão
 * `alunos.ver_todos`. Professor (sem a permissão) só enxerga os estudantes
 * das próprias turmas; admin (com a permissão) enxerga todos.
 */
class EscopoEstudantesTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected Student $alunoDoProfessor;
    protected Student $alunoDeOutraTurma;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();

        $ano = date('Y');

        $turmaA = SchoolClass::create(['school_id' => $this->escola->id, 'name' => 'A', 'shift' => 'Matutino', 'year' => $ano]);
        $turmaB = SchoolClass::create(['school_id' => $this->escola->id, 'name' => 'B', 'shift' => 'Matutino', 'year' => $ano]);

        // Professor leciona só a turma A
        $this->professor->schoolClasses()->attach($turmaA->id, ['subject' => 'matematica']);

        $this->alunoDoProfessor = Student::create([
            'school_id' => $this->escola->id, 'name' => 'Aluno A', 'registration_number' => 'A1', 'birth_date' => '2010-01-01',
        ]);
        $this->alunoDeOutraTurma = Student::create([
            'school_id' => $this->escola->id, 'name' => 'Aluno B', 'registration_number' => 'B1', 'birth_date' => '2010-01-01',
        ]);

        $this->alunoDoProfessor->schoolClasses()->attach($turmaA->id);
        $this->alunoDeOutraTurma->schoolClasses()->attach($turmaB->id);
    }

    public function test_permissao_ver_todos_por_papel(): void
    {
        $this->assertTrue($this->secretaria->podeVerTodosEstudantes(), 'admin deveria ver todos');
        $this->assertFalse($this->professor->podeVerTodosEstudantes(), 'professor NÃO deveria ver todos');
    }

    public function test_professor_ve_apenas_estudantes_das_proprias_turmas(): void
    {
        $this->loginComo($this->professor);

        $ids = Student::visiveisPara($this->professor)->pluck('id')->all();

        $this->assertContains($this->alunoDoProfessor->id, $ids);
        $this->assertNotContains($this->alunoDeOutraTurma->id, $ids);
        $this->assertCount(1, $ids);
    }

    public function test_admin_ve_todos_os_estudantes(): void
    {
        $this->loginComo($this->secretaria);

        $ids = Student::visiveisPara($this->secretaria)->pluck('id')->all();

        $this->assertContains($this->alunoDoProfessor->id, $ids);
        $this->assertContains($this->alunoDeOutraTurma->id, $ids);
    }

    public function test_guard_de_acesso_a_estudante_individual(): void
    {
        $this->loginComo($this->professor);

        $this->assertTrue($this->professor->podeAcessarEstudante($this->alunoDoProfessor));
        $this->assertFalse($this->professor->podeAcessarEstudante($this->alunoDeOutraTurma));

        // Admin acessa qualquer estudante
        $this->assertTrue($this->secretaria->podeAcessarEstudante($this->alunoDeOutraTurma));
    }
}
