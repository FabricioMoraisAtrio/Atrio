<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Banco de metas reutilizáveis da escola. Sugestões de metas de PEI
     * (por categoria) que abastecem o cadastro de metas de cada aluno.
     */
    public function up(): void
    {
        if (Schema::hasTable('goal_templates')) {
            return;
        }

        Schema::create('goal_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('categoria'); // academica|socioemocional|funcional
            $table->string('texto', 500);
            $table->string('tag')->nullable();
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();

            $table->index(['school_id', 'categoria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_templates');
    }
};
