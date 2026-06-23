<?php

use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Completa a grade curricular padrão (BNCC + regente) das escolas que ficaram
     * com a grade incompleta. A semeadura agora preenche apenas as matérias
     * faltantes (por slug), sem duplicar as já existentes.
     */
    public function up(): void
    {
        School::query()->each(function (School $school) {
            Subject::seedDefaultsForSchool($school->id);
        });
    }

    public function down(): void
    {
        // Não remove matérias: podem já estar em uso por PEIs/turmas.
    }
};
