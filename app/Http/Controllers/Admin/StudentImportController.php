<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\School;
use App\Services\StudentImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentImportController extends Controller
{
    /** Cabeçalho padrão do CSV (ordem das colunas). */
    public const HEADER = [
        'nome', 'matricula', 'data_nascimento',
        'responsavel_nome', 'responsavel_email', 'responsavel_telefone',
        'responsavel_2_nome', 'responsavel_2_email', 'responsavel_2_telefone',
        'turma', 'condicao',
    ];

    public function index(School $school)
    {
        return view('admin.schools.import.upload', ['school' => $school, 'header' => self::HEADER]);
    }

    /** Baixa um modelo CSV com o cabeçalho padrão e uma linha de exemplo. */
    public function template(School $school): StreamedResponse
    {
        $exemplo = [
            'João da Silva', '2026001', '15/03/2014',
            'Maria da Silva', 'maria@email.com', '(11) 99999-0000',
            'José da Silva', 'jose@email.com', '(11) 98888-0000',
            '6º A', 'TEA — laudo em anexo',
        ];

        return response()->streamDownload(function () use ($exemplo) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para acentos no Excel
            fputcsv($out, self::HEADER, ';');
            fputcsv($out, $exemplo, ';');
            fclose($out);
        }, 'modelo_importacao_alunos.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function preview(Request $request, School $school, StudentImporter $importer)
    {
        $request->validate(['arquivo' => 'required|file|mimes:csv,txt|max:5120']);

        $path = $request->file('arquivo')->store('imports', 'local');
        session([
            'admin_import_file' => $path,
            'admin_import_name' => $request->file('arquivo')->getClientOriginalName(),
        ]);

        $rows    = $importer->parse(Storage::disk('local')->path($path));
        $analise = $importer->analyze($rows, (int) $school->id);

        return view('admin.schools.import.preview', [
            'school' => $school,
            'rows'   => $analise['rows'],
            'resumo' => $analise['resumo'],
            'nome'   => session('admin_import_name'),
        ]);
    }

    public function confirm(Request $request, School $school, StudentImporter $importer)
    {
        $request->validate(['confirma' => 'accepted']);

        $path = session('admin_import_file');
        if (! $path || ! Storage::disk('local')->exists($path)) {
            return redirect()->route('admin.schools.import', $school)
                ->with('error', 'Arquivo de importação expirou. Envie novamente.');
        }

        $rows = $importer->parse(Storage::disk('local')->path($path));
        $out  = $importer->commit($rows, (int) $school->id);

        DB::table('student_imports')->insert([
            'school_id'     => $school->id,
            'user_id'       => auth('admin')->id(),
            'filename'      => session('admin_import_name'),
            'total_rows'    => count($rows),
            'created_count' => $out['created'],
            'updated_count' => $out['updated'],
            'skipped_count' => $out['skipped'],
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        Storage::disk('local')->delete($path);
        session()->forget(['admin_import_file', 'admin_import_name']);

        AdminLog::record('alunos_importados', "Importou alunos em \"{$school->name}\": {$out['created']} criado(s), {$out['updated']} atualizado(s)", (int) $school->id);

        return redirect()->route('admin.schools.edit', $school)
            ->with('success', "Importação concluída para {$school->name}: {$out['created']} criado(s), {$out['updated']} atualizado(s), {$out['skipped']} ignorado(s).");
    }
}
