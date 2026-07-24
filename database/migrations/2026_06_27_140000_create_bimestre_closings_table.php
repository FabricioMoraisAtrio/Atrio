<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fechamento de bimestre por aluno/ano. A existência de uma linha significa
     * que aquele bimestre está FECHADO (avaliações travadas). Reabrir = excluir.
     * snapshot guarda o resultado congelado (% e nº de metas avaliadas).
     */
    public function up(): void
    {
        if (Schema::hasTable('bimestre_closings')) {
            return;
        }

        Schema::create('bimestre_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('bimestre'); // 1..4
            $table->json('snapshot')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'year', 'bimestre']);
            $table->index(['school_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimestre_closings');
    }
};
