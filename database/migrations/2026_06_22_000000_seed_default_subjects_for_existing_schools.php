<?php

use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Semeia a grade curricular padrão (BNCC + regente) para escolas que
     * ainda não possuem nenhuma matéria cadastrada.
     */
    public function up(): void
    {
        School::query()->each(function (School $school) {
            Subject::seedDefaultsForSchool($school->id);
        });
    }

    public function down(): void
    {
        // Não remove matérias: poderiam já estar em uso por PEIs existentes.
    }
};
