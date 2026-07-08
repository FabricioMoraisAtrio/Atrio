<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Services\StudentImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;

class StudentImportTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();
    }

    /**
     * Linha de CSV sem data de nascimento deve criar o aluno mesmo assim
     * (birth_date nullable) — antes quebrava com 1364 no MySQL em produção.
     */
    public function test_importa_aluno_sem_data_de_nascimento(): void
    {
        $importer = app(StudentImporter::class);

        $out = $importer->commit([
            ['name' => 'Aluno Sem Data', 'registration_number' => '900', 'birth_date' => null],
        ], (int) $this->escola->id);

        $this->assertSame(1, $out['created']);
        $this->assertDatabaseHas('students', [
            'school_id'           => $this->escola->id,
            'registration_number' => '900',
            'birth_date'          => null,
        ]);
    }

    public function test_importa_aluno_com_data_valida(): void
    {
        $importer = app(StudentImporter::class);

        $out = $importer->commit([
            ['name' => 'Aluno Com Data', 'registration_number' => '901', 'birth_date' => '2014-03-15'],
        ], (int) $this->escola->id);

        $this->assertSame(1, $out['created']);
        $student = Student::withoutGlobalScopes()->where('registration_number', '901')->first();
        $this->assertSame('2014-03-15', $student->birth_date->toDateString());
    }
}
