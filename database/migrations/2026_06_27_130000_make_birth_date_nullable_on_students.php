<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Torna students.birth_date NULLABLE.
     *
     * A importação de alunos por CSV é tolerante: linhas sem data de nascimento
     * (ou com data em formato não reconhecido) devem criar o aluno mesmo assim,
     * para ser completado depois. Com a coluna NOT NULL, o INSERT sem birth_date
     * quebrava no MySQL em strict mode ("Field 'birth_date' doesn't have a default
     * value" — erro 1364). O cadastro individual continua exigindo a data via
     * validação do formulário.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('students', 'birth_date')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Sem reversão: manter nullable evita o 500 na importação.
    }
};
