<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Garante que document_access_logs.document_id seja NULLABLE.
     *
     * A migration original criava a coluna como NOT NULL e a de 2026_06_15 só
     * removia a foreign key quando a tabela já existia — nunca afrouxava o
     * NOT NULL. Em ambientes recém-migrados (migrate:fresh, instalação nova) a
     * coluna ficava NOT NULL, e o log de ações sem documento (criar/editar aluno,
     * usuário etc., com document_id nulo) quebrava com violação de constraint.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('document_access_logs', 'document_id')) {
            return;
        }

        Schema::table('document_access_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('document_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Sem reversão: manter nullable é o estado correto.
    }
};
