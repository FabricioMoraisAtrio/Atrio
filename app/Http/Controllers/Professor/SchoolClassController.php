<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    public function index()
    {
        $turmas = SchoolClass::where('school_id', session('school_id'))
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return view('professor.turmas.index', compact('turmas'));
    }
}
