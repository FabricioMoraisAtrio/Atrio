<?php

namespace App\Http\Controllers\Pai;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAccessLog;

class DocumentPdfController extends Controller
{
    public function __invoke(Document $documento)
    {
        // Verifica se o documento pertence a um filho do pai
        $filhoIds = auth()->user()->children()->pluck('students.id');

        if (! $filhoIds->contains($documento->student_id)) {
            abort(403);
        }

        DocumentAccessLog::create([
            'document_id' => $documento->id,
            'user_id'     => auth()->id(),
            'action'      => 'downloaded_pai',
            'ip'          => request()->ip(),
            'accessed_at' => now(),
        ]);

        // Reutiliza o PDF da secretaria
        $controller = new \App\Http\Controllers\Secretaria\DocumentPdfController();
        return $controller($documento);
    }
}