<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Metas acadêmicas customizadas individualmente por aluno (por matéria e ano),
     * cadastradas pelo administrador da escola. Substituem o catálogo fixo de
     * metas acadêmicas por matéria no novo fluxo do PEI.
     */
    public function up(): void
    {
        Schema::create('student_academic_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->text('meta');
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->timestamps();

            $table->index(['student_id', 'subject_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_academic_goals');
    }
};
