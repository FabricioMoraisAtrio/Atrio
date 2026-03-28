<?php

namespace App\Http\Controllers\Pai;

use App\Http\Controllers\Controller;
use App\Models\Laudo;
use Illuminate\Support\Facades\Storage;

class LaudoController extends Controller
{
    public function download(Laudo $laudo)
    {
        $filhoIds = auth()->user()->children()->pluck('students.id');

        if (! $filhoIds->contains($laudo->student_id)) {
            abort(403);
        }

        return Storage::disk('private')->download(
            $laudo->arquivo,
            $laudo->tipo_label . '_' . $laudo->student->name . '_' . $laudo->data_laudo->format('d-m-Y') . '.pdf'
        );
    }
}