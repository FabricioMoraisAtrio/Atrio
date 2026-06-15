<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentPhotoController extends Controller
{
    /**
     * Serve a foto do aluno diretamente do disco, sem depender do link
     * simbólico public/storage (que pode estar ausente/quebrado em alguns ambientes).
     */
    public function __invoke(Student $aluno)
    {
        abort_unless($aluno->photo, 404);

        $path = storage_path('app/public/' . $aluno->photo);

        abort_unless(file_exists($path), 404);

        return response()->file($path);
    }
}
