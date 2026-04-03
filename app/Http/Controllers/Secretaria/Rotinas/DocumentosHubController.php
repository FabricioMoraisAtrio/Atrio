<?php

namespace App\Http\Controllers\Secretaria\Rotinas;

use App\Http\Controllers\Controller;

class DocumentosHubController extends Controller
{
    public function __invoke()
    {
        return view('secretaria.rotinas.documentos.index');
    }
}
