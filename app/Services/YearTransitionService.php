<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

/**
 * Promoção assistida de fim de ano: cria as turmas do próximo ano e
 * matricula os alunos conforme o mapeamento, copiando os professores.
 * Tudo idempotente (reexecutar não duplica) e isolado por escola (SchoolScope).
 */
class YearTransitionService
{
    public function currentYear(): int
    {
        return (int) date('Y');
    }

    /** Turmas de um ano (escopo da escola atual). */
    public function classesForYear(int $year)
    {
        return SchoolClass::where('year', $year)
            ->withCount('students')
            ->orderBy('name')
            ->get();
    }

    public function nextYearHasClasses(int $targetYear): bool
    {
        return SchoolClass::where('year', $targetYear)->exists();
    }

    /**
     * Executa a virada.
     *
     * @param int   $targetYear     ano de destino (geralmente atual+1)
     * @param array $classDest      [source_class_id => nome da turma destino]
     * @param array $studentStatus  [student_id => 'promovido'|'retido'|'saiu']
     * @return array  resumo { classes_created, students_enrolled, teachers_copied }
     */
    public function prepare(int $targetYear, array $classDest, array $studentStatus): array
    {
        $schoolId = (int) session('school_id');
        $sourceYear = $targetYear - 1;

        $resumo = ['classes_created' => 0, 'students_enrolled' => 0, 'teachers_copied' => 0];

        DB::transaction(function () use ($targetYear, $sourceYear, $schoolId, $classDest, $studentStatus, &$resumo) {
            $sources = SchoolClass::where('year', $sourceYear)
                ->with(['students:id', 'teachers:id'])
                ->get();

            // cache de turmas destino por (nome|turno) para não recriar
            $destCache = [];
            $resolveClass = function (string $name, string $shift) use ($targetYear, $schoolId, &$destCache, &$resumo): SchoolClass {
                $key = mb_strtolower(trim($name)) . '|' . $shift;
                if (isset($destCache[$key])) {
                    return $destCache[$key];
                }
                $class = SchoolClass::where('year', $targetYear)
                    ->where('name', trim($name))
                    ->where('shift', $shift)
                    ->first();
                if (! $class) {
                    $class = SchoolClass::create([
                        'school_id' => $schoolId,
                        'name'      => trim($name),
                        'shift'     => $shift,
                        'year'      => $targetYear,
                    ]);
                    $resumo['classes_created']++;
                }
                return $destCache[$key] = $class;
            };

            foreach ($sources as $source) {
                $destName = trim($classDest[$source->id] ?? $source->name) ?: $source->name;
                $destClass = $resolveClass($destName, $source->shift);

                // copia professores da turma de origem para a turma destino
                $teacherIds = $source->teachers->pluck('id')->all();
                if ($teacherIds) {
                    // preserva a matéria de cada vínculo
                    $rows = DB::table('school_class_user')->where('school_class_id', $source->id)->get();
                    foreach ($rows as $row) {
                        $exists = DB::table('school_class_user')
                            ->where('school_class_id', $destClass->id)
                            ->where('user_id', $row->user_id)
                            ->where('subject', $row->subject)
                            ->exists();
                        if (! $exists) {
                            DB::table('school_class_user')->insert([
                                'school_class_id' => $destClass->id,
                                'user_id'         => $row->user_id,
                                'subject'         => $row->subject,
                                'created_at'      => now(),
                                'updated_at'      => now(),
                            ]);
                            $resumo['teachers_copied']++;
                        }
                    }
                }

                foreach ($source->students as $student) {
                    $status = $studentStatus[$student->id] ?? 'promovido';
                    if ($status === 'saiu') {
                        continue;
                    }
                    $turma = $status === 'retido'
                        ? $resolveClass($source->name, $source->shift) // permanece com o nome da origem
                        : $destClass;

                    $already = DB::table('school_class_student')
                        ->where('school_class_id', $turma->id)
                        ->where('student_id', $student->id)
                        ->exists();
                    if (! $already) {
                        DB::table('school_class_student')->insert([
                            'school_class_id' => $turma->id,
                            'student_id'      => $student->id,
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]);
                        $resumo['students_enrolled']++;
                    }
                }
            }
        });

        return $resumo;
    }
}
