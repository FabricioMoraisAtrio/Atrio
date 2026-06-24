<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Permite que as metas do PEI sejam de três categorias (acadêmica,
     * socioemocional, funcional) no mesmo formato (lista de metas avaliáveis).
     * As metas socioemocionais/funcionais não pertencem a uma matéria, então
     * subject_id passa a ser opcional.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('student_academic_goals', 'categoria')) {
            Schema::table('student_academic_goals', function (Blueprint $table) {
                $table->string('categoria')->default('academica')->after('subject_id');
            });
        }

        Schema::table('student_academic_goals', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('student_academic_goals', 'categoria')) {
            Schema::table('student_academic_goals', function (Blueprint $table) {
                $table->dropColumn('categoria');
            });
        }
    }
};
