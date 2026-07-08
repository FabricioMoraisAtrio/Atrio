<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Evolução (acompanhamento) das metas do PEI por bimestre.
     * Uma linha por meta × bimestre avaliado, com status e observação.
     */
    public function up(): void
    {
        if (Schema::hasTable('goal_progresses')) {
            return;
        }

        Schema::create('goal_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_academic_goal_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('bimestre'); // 1..4
            $table->string('status')->default('nao_avaliado'); // nao_atingiu|em_progresso|atingiu|nao_avaliado
            $table->text('observacao')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_academic_goal_id', 'bimestre']);
            $table->index(['school_id', 'year', 'bimestre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_progresses');
    }
};
