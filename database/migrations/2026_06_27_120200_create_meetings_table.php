<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro de reuniões vinculadas ao aluno (PEI, família, equipe, devolutivas).
     */
    public function up(): void
    {
        if (Schema::hasTable('meetings')) {
            return;
        }

        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('data');
            $table->string('tipo'); // pei|familia|equipe|devolutiva|outro
            $table->text('participantes');
            $table->text('pauta')->nullable();
            $table->text('encaminhamentos')->nullable();
            $table->text('observacoes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['school_id', 'student_id', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
