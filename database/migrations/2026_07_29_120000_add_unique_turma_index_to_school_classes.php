<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Impede duplicidade de turma no banco: mesma escola + nome + turno + ano.
     * Antes de criar o índice, renomeia eventuais duplicatas já existentes
     * (acrescentando o id) para não perder registros nem falhar a migração.
     */
    public function up(): void
    {
        $grupos = DB::table('school_classes')
            ->select('school_id', 'name', 'shift', 'year', DB::raw('MIN(id) as manter'))
            ->groupBy('school_id', 'name', 'shift', 'year')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($grupos as $g) {
            $ids = DB::table('school_classes')
                ->where('school_id', $g->school_id)
                ->where('name', $g->name)
                ->where('shift', $g->shift)
                ->where('year', $g->year)
                ->orderBy('id')
                ->pluck('id');

            foreach ($ids as $id) {
                if ($id == $g->manter) {
                    continue; // mantém a primeira turma do grupo com o nome original
                }
                DB::table('school_classes')->where('id', $id)->update([
                    'name' => mb_substr($g->name, 0, 42) . ' (#' . $id . ')',
                ]);
            }
        }

        Schema::table('school_classes', function (Blueprint $table) {
            $table->unique(['school_id', 'name', 'shift', 'year'], 'school_classes_turma_unique');
        });
    }

    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropUnique('school_classes_turma_unique');
        });
    }
};
