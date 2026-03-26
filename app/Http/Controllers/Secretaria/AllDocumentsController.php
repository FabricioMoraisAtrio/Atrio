<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;

class AllDocumentsController extends Controller
{
    public function __invoke()
    {
        $documentos = Document::with('student', 'author')
            ->latest()
            ->get()
            ->groupBy('type');

        return view('secretaria.documentos.all', compact('documentos'));
    }
}