<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Importa alunos a partir de uma planilha CSV.
 * - detecta colunas por cabeçalho (com sinônimos);
 * - deduplica por matrícula + escola (idempotente);
 * - nunca apaga; na atualização preenche só campos vazios (não sobrescreve);
 * - condição entra como sugestão (seta is_atypical + condition; CID fica manual).
 */
class StudentImporter
{
    /** Sinônimos de cabeçalho → campo do Átrio. */
    private const ALIASES = [
        'name'                 => ['nome', 'name', 'aluno', 'estudante', 'nome completo', 'nome do aluno', 'nome aluno'],
        'registration_number'  => ['matricula', 'ra', 'registro', 'registration', 'registration number', 'codigo', 'cod', 'matricula do aluno', 'numero matricula'],
        'birth_date'           => ['nascimento', 'data de nascimento', 'data nascimento', 'dt nascimento', 'birth', 'birth date', 'nasc'],
        'responsavel_nome'     => ['responsavel', 'responsavel nome', 'nome do responsavel', 'filiacao', 'responsavel 1', 'mae', 'pai'],
        'responsavel_email'    => ['email do responsavel', 'responsavel email', 'email responsavel', 'email'],
        'responsavel_telefone' => ['telefone', 'telefone do responsavel', 'responsavel telefone', 'celular', 'contato', 'fone', 'whatsapp'],
        'turma'                => ['turma', 'classe', 'sala', 'serie', 'ano turma', 'turma do aluno'],
        'condicao'             => ['condicao', 'diagnostico', 'cid', 'laudo', 'necessidade', 'deficiencia'],
    ];

    /** Lê o CSV e devolve linhas normalizadas. */
    public function parse(string $absolutePath): array
    {
        if (! is_file($absolutePath)) {
            return [];
        }

        $fh = fopen($absolutePath, 'r');
        if (! $fh) {
            return [];
        }

        // detecta delimitador na primeira linha
        $firstLine = fgets($fh);
        rewind($fh);
        $delimiter = (substr_count($firstLine, ';') >= substr_count($firstLine, ',')) ? ';' : ',';

        $header = fgetcsv($fh, 0, $delimiter);
        if (! $header) {
            fclose($fh);
            return [];
        }
        // BOM
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }
        $map = $this->mapHeader($header);

        $rows = [];
        $line = 1;
        while (($cols = fgetcsv($fh, 0, $delimiter)) !== false) {
            $line++;
            if (count(array_filter($cols, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue; // linha vazia
            }
            $row = ['_line' => $line];
            foreach ($map as $i => $field) {
                $row[$field] = isset($cols[$i]) ? trim((string) $cols[$i]) : null;
            }
            $row['birth_date'] = $this->parseDate($row['birth_date'] ?? null);
            $rows[] = $row;
        }
        fclose($fh);

        return $rows;
    }

    /** Analisa as linhas: ação (criar/atualizar/erro) + erros + resumo. */
    public function analyze(array $rows): array
    {
        $schoolId = (int) session('school_id');
        $analisadas = [];
        $resumo = ['criar' => 0, 'atualizar' => 0, 'erro' => 0, 'total' => count($rows)];

        foreach ($rows as $row) {
            $errors = [];
            $name = $row['name'] ?? null;
            $reg  = $row['registration_number'] ?? null;

            if (! $name) {
                $errors[] = 'Sem nome';
            }
            if (! $reg) {
                $errors[] = 'Sem matrícula';
            }

            $existing = null;
            if ($reg) {
                $existing = Student::where('school_id', $schoolId)
                    ->where('registration_number', $reg)->first();
            }

            $action = $errors ? 'erro' : ($existing ? 'atualizar' : 'criar');
            $resumo[$action]++;

            $row['_action'] = $action;
            $row['_errors'] = $errors;
            $row['_existing_id'] = $existing?->id;
            $analisadas[] = $row;
        }

        return ['rows' => $analisadas, 'resumo' => $resumo];
    }

    /** Aplica a importação. Retorna { created, updated, skipped }. */
    public function commit(array $rows): array
    {
        $schoolId = (int) session('school_id');
        $year = (int) date('Y');
        $out = ['created' => 0, 'updated' => 0, 'skipped' => 0];

        DB::transaction(function () use ($rows, $schoolId, $year, &$out) {
            $turmaCache = [];

            foreach ($rows as $row) {
                $name = $row['name'] ?? null;
                $reg  = $row['registration_number'] ?? null;
                if (! $name || ! $reg) {
                    $out['skipped']++;
                    continue;
                }

                $student = Student::where('school_id', $schoolId)
                    ->where('registration_number', $reg)->first();

                if (! $student) {
                    $student = new Student(['school_id' => $schoolId, 'name' => $name, 'registration_number' => $reg]);
                    $this->applyFields($student, $row, true);
                    $student->save();
                    $out['created']++;
                } else {
                    $this->applyFields($student, $row, false); // só preenche vazios
                    $student->save();
                    $out['updated']++;
                }

                // turma → matrícula no ano corrente
                $turma = $row['turma'] ?? null;
                if ($turma) {
                    $key = mb_strtolower($turma);
                    if (! isset($turmaCache[$key])) {
                        $turmaCache[$key] = SchoolClass::where('year', $year)->where('name', $turma)->first()
                            ?? SchoolClass::create(['school_id' => $schoolId, 'name' => $turma, 'shift' => 'Matutino', 'year' => $year]);
                    }
                    $classId = $turmaCache[$key]->id;
                    $exists = DB::table('school_class_student')
                        ->where('school_class_id', $classId)->where('student_id', $student->id)->exists();
                    if (! $exists) {
                        DB::table('school_class_student')->insert([
                            'school_class_id' => $classId, 'student_id' => $student->id,
                            'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }
            }
        });

        return $out;
    }

    /** Aplica campos no aluno. $isNew = criar (seta tudo); senão só preenche vazios. */
    private function applyFields(Student $student, array $row, bool $isNew): void
    {
        $campos = ['name', 'birth_date', 'responsavel_nome', 'responsavel_email', 'responsavel_telefone'];
        foreach ($campos as $f) {
            $val = $row[$f] ?? null;
            if ($val === null || $val === '') {
                continue;
            }
            if ($isNew || empty($student->{$f})) {
                $student->{$f} = $val;
            }
        }
        // condição → sugestão (não sobrescreve; CID continua manual)
        $cond = $row['condicao'] ?? null;
        if ($cond && ($isNew || empty($student->condition))) {
            $student->condition  = $cond;
            $student->is_atypical = true;
        }
    }

    private function mapHeader(array $header): array
    {
        $map = [];
        foreach ($header as $i => $col) {
            $norm = $this->normalize($col);
            foreach (self::ALIASES as $field => $alts) {
                if (in_array($norm, array_map([$this, 'normalize'], $alts), true)) {
                    $map[$i] = $field;
                    break;
                }
            }
        }
        return $map;
    }

    private function normalize(string $s): string
    {
        $s = mb_strtolower(trim($s));
        $s = strtr($s, ['á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ç' => 'c']);
        return preg_replace('/[^a-z0-9 ]/', '', $s);
    }

    private function parseDate(?string $v): ?string
    {
        if (! $v) {
            return null;
        }
        foreach (['d/m/Y', 'Y-m-d', 'd-m-Y', 'd.m.Y'] as $fmt) {
            try {
                $d = Carbon::createFromFormat($fmt, trim($v));
                if ($d && $d->year > 1900 && $d->year <= (int) date('Y')) {
                    return $d->toDateString();
                }
            } catch (\Throwable $e) {
                // tenta o próximo formato
            }
        }
        return null;
    }
}
