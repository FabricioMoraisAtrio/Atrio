<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Consolida duplicatas antes de criar o índice único
        $duplicates = DB::table('documents')
            ->select('student_id', 'type', 'year')
            ->groupBy('student_id', 'type', 'year')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            $rows = DB::table('documents')
                ->where('student_id', $dup->student_id)
                ->where('type', $dup->type)
                ->where('year', $dup->year)
                ->orderByDesc('updated_at')
                ->get();

            $keeper = $rows->first();

            if ($dup->type === 'pei') {
                $subjects = [];
                foreach ($rows as $row) {
                    $content = json_decode($row->content, true) ?? [];
                    $materia = $content['materia'] ?? ('autor_' . $row->author_id);
                    $slug    = \Illuminate\Support\Str::slug($materia, '_');
                    $subjects[$slug] = [
                        'subject_name'               => $materia,
                        'teacher_id'                 => $row->author_id,
                        'teacher_name'               => null,
                        'updated_at'                 => $row->updated_at,
                        'habilidades_academicas'     => $content['habilidades_academicas'] ?? null,
                        'habilidades_socioemocionais' => $content['habilidades_socioemocionais'] ?? null,
                        'desenvolvimento_global'     => $content['habilidades_funcionais'] ?? null,
                        'observacoes_livres'         => $content['observacoes_livres'] ?? null,
                    ];
                }

                DB::table('documents')
                    ->where('id', $keeper->id)
                    ->update(['content' => json_encode(['subjects' => $subjects])]);
            }

            $idsToDelete = $rows->skip(1)->pluck('id');
            DB::table('documents')->whereIn('id', $idsToDelete)->delete();
        }

        // Remove índice antigo se existir (compatibilidade)
        try {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropUnique('documents_student_type_year_author_unique');
            });
        } catch (\Exception $e) {
            // índice não existia, prossegue
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->unique(['student_id', 'type', 'year'], 'documents_student_type_year_unique');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique('documents_student_type_year_unique');
        });
    }
};
