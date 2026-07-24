<?php

namespace Tests\Feature;

use App\Models\GoalProgress;
use App\Models\Laudo;
use App\Models\Meeting;
use App\Models\Observation;
use App\Models\Student;
use App\Models\StudentAcademicGoal;
use App\Services\StudentTimelineService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;

class RotinasInclusaoTest extends TestCase
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
            'name'                => 'Aluno Rotinas',
            'registration_number' => '300',
            'birth_date'          => '2010-01-01',
        ]);
    }

    private function criarMeta(string $categoria = 'socioemocional'): StudentAcademicGoal
    {
        return StudentAcademicGoal::create([
            'school_id'  => $this->escola->id,
            'student_id' => $this->aluno->id,
            'subject_id' => null,
            'categoria'  => $categoria,
            'year'       => (int) date('Y'),
            'meta'       => 'Meta de teste',
            'ordem'      => 1,
        ]);
    }

    // ─── Rotina 1: evolução (acompanhamento bimestral) das metas ───

    public function test_salva_evolucao_da_meta_por_bimestre(): void
    {
        $meta = $this->criarMeta();

        $this->put(route('secretaria.alunos.metas-evolucao.update', $this->aluno), [
            'status' => [$meta->id => [1 => 'atingiu', 2 => 'em_progresso']],
        ])->assertRedirect(route('secretaria.alunos.linha-do-tempo', $this->aluno));

        $this->assertDatabaseHas('goal_progresses', [
            'student_academic_goal_id' => $meta->id,
            'bimestre'                 => 1,
            'status'                   => 'atingiu',
            'evaluated_by'             => $this->secretaria->id,
        ]);
        $this->assertDatabaseHas('goal_progresses', [
            'student_academic_goal_id' => $meta->id,
            'bimestre'                 => 2,
            'status'                   => 'em_progresso',
        ]);
    }

    public function test_nao_avaliado_remove_progresso_existente(): void
    {
        $meta = $this->criarMeta();
        GoalProgress::create([
            'school_id'                => $this->escola->id,
            'student_academic_goal_id' => $meta->id,
            'year'                     => (int) date('Y'),
            'bimestre'                 => 1,
            'status'                   => 'atingiu',
        ]);

        $this->put(route('secretaria.alunos.metas-evolucao.update', $this->aluno), [
            'status' => [$meta->id => [1 => 'nao_avaliado']],
        ])->assertRedirect();

        $this->assertDatabaseMissing('goal_progresses', [
            'student_academic_goal_id' => $meta->id,
            'bimestre'                 => 1,
        ]);
    }

    // ─── Rotina 2: banco de metas reutilizáveis ───

    public function test_salva_banco_de_metas_ignorando_vazias(): void
    {
        $this->put(route('secretaria.metas.banco.update'), [
            'metas' => [
                'socioemocional' => ['Regular emoções', ''],
                'funcional'      => ['Higiene pessoal'],
            ],
        ])->assertRedirect(route('secretaria.metas.banco.edit'));

        $this->assertDatabaseHas('goal_templates', [
            'school_id' => $this->escola->id,
            'categoria' => 'socioemocional',
            'texto'     => 'Regular emoções',
        ]);
        $this->assertDatabaseHas('goal_templates', [
            'categoria' => 'funcional',
            'texto'     => 'Higiene pessoal',
        ]);
        $this->assertDatabaseMissing('goal_templates', ['texto' => '']);
    }

    // ─── Rotina 3: registro de reuniões ───

    public function test_registra_reuniao(): void
    {
        $this->post(route('secretaria.alunos.reunioes.store', $this->aluno), [
            'data'          => date('Y-m-d'),
            'tipo'          => 'familia',
            'participantes' => 'Coordenação e família',
            'pauta'         => 'Adaptações necessárias',
        ])->assertRedirect(route('secretaria.alunos.reunioes.index', $this->aluno));

        $this->assertDatabaseHas('meetings', [
            'student_id'    => $this->aluno->id,
            'tipo'          => 'familia',
            'participantes' => 'Coordenação e família',
            'created_by'    => $this->secretaria->id,
        ]);
    }

    public function test_nao_acessa_reuniao_de_outro_aluno(): void
    {
        $outro = Student::create([
            'school_id'           => $this->escola->id,
            'name'                => 'Outro Aluno',
            'registration_number' => '301',
            'birth_date'          => '2011-01-01',
        ]);
        $reuniao = Meeting::create([
            'school_id'     => $this->escola->id,
            'student_id'    => $outro->id,
            'data'          => date('Y-m-d'),
            'tipo'          => 'equipe',
            'participantes' => 'Equipe pedagógica',
            'created_by'    => $this->secretaria->id,
        ]);

        // Reunião do "outro" acessada pela URL do aluno errado → 404.
        $this->get(route('secretaria.alunos.reunioes.edit', [$this->aluno, $reuniao]))
            ->assertStatus(404);
    }

    // ─── Roadmap: linha do tempo ───

    public function test_linha_do_tempo_agrega_eventos_de_varias_origens(): void
    {
        $meta = $this->criarMeta();
        GoalProgress::create([
            'school_id'                => $this->escola->id,
            'student_academic_goal_id' => $meta->id,
            'year'                     => (int) date('Y'),
            'bimestre'                 => 1,
            'status'                   => 'atingiu',
        ]);
        Meeting::create([
            'school_id' => $this->escola->id, 'student_id' => $this->aluno->id,
            'data' => date('Y-m-d'), 'tipo' => 'familia', 'participantes' => 'Família',
            'created_by' => $this->secretaria->id,
        ]);
        Laudo::create([
            'school_id' => $this->escola->id, 'student_id' => $this->aluno->id,
            'uploaded_by' => $this->secretaria->id, 'tipo' => 'medico',
            'descricao' => 'Laudo', 'arquivo' => 'laudos/x.pdf', 'data_laudo' => date('Y-m-d'),
        ]);
        Observation::create([
            'school_id' => $this->escola->id, 'student_id' => $this->aluno->id,
            'user_id' => $this->secretaria->id, 'content' => 'Observação de teste',
            'urgency' => 'critico', 'category' => 'comportamento',
        ]);

        $eventos = app(StudentTimelineService::class)->build($this->aluno);
        $tipos = array_column($eventos, 'tipo');

        $this->assertCount(4, $eventos);
        $this->assertEqualsCanonicalizing(['meta', 'reuniao', 'laudo', 'observacao'], $tipos);

        // A tela por aluno renderiza os eventos (200).
        $this->get(route('secretaria.alunos.linha-do-tempo', $this->aluno))
            ->assertStatus(200)
            ->assertSee('Linha do Tempo');
    }
}
