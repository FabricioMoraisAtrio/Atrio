<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAccessLog;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class DocumentWordController extends Controller
{
    public function __invoke(Document $documento)
    {
        $documento->load('student', 'author');

        DocumentAccessLog::create([
            'document_id' => $documento->id,
            'user_id'     => auth()->id(),
            'action'      => 'exported',
            'ip'          => request()->ip(),
            'accessed_at' => now(),
        ]);

        return (new \App\Http\Controllers\Professor\DocumentWordController)->gerarWord($documento);
    }
}