<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Services\StudentImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function index()
    {
        return view('secretaria.import.upload');
    }

    public function preview(Request $request, StudentImporter $importer)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        // guarda em disco privado para reprocessar no confirmar
        $path = $request->file('arquivo')->store('imports', 'local');
        session(['import_file' => $path, 'import_name' => $request->file('arquivo')->getClientOriginalName()]);

        $rows = $importer->parse(Storage::disk('local')->path($path));
        $analise = $importer->analyze($rows);

        return view('secretaria.import.preview', [
            'rows'   => $analise['rows'],
            'resumo' => $analise['resumo'],
            'nome'   => session('import_name'),
        ]);
    }

    public function confirm(Request $request, StudentImporter $importer)
    {
        $request->validate(['confirma' => 'accepted']);

        $path = session('import_file');
        if (! $path || ! Storage::disk('local')->exists($path)) {
            return redirect()->route('secretaria.alunos.importar')
                ->with('error', 'Arquivo de importação expirou. Envie novamente.');
        }

        $rows = $importer->parse(Storage::disk('local')->path($path));
        $out  = $importer->commit($rows);

        // auditoria
        DB::table('student_imports')->insert([
            'school_id'     => (int) session('school_id'),
            'user_id'       => auth()->id(),
            'filename'      => session('import_name'),
            'total_rows'    => count($rows),
            'created_count' => $out['created'],
            'updated_count' => $out['updated'],
            'skipped_count' => $out['skipped'],
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // limpeza
        Storage::disk('local')->delete($path);
        session()->forget(['import_file', 'import_name']);

        return redirect()->route('secretaria.alunos.index')
            ->with('success', "Importação concluída: {$out['created']} criado(s), {$out['updated']} atualizado(s), {$out['skipped']} ignorado(s).");
    }
}
