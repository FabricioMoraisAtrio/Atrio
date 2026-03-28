<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Laudo;

class LaudoController extends Controller
{
    public function index()
    {
        $laudos = Laudo::where('school_id', session('school_id'))
            ->with('student', 'uploader')
            ->latest()
            ->get()
            ->groupBy('tipo');

        return view('professor.laudos.index', compact('laudos'));
    }

    public function download(Laudo $laudo)
    {
        abort_unless($laudo->school_id === session('school_id'), 403);

        return \Illuminate\Support\Facades\Storage::disk('private')->download(
            $laudo->arquivo,
            $laudo->tipo_label . '_' . $laudo->student->name . '_' . $laudo->data_laudo->format('d-m-Y') . '.pdf'
        );
    }
}
