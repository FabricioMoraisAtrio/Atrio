<?php

namespace App\Http\Controllers\Pai;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $filhos = auth()->user()->children()->with([
            'documents' => fn($q) => $q->where('year', date('Y')),
            'schoolClasses',
            'laudos',
        ])->get();

        return view('pai.dashboard', compact('filhos'));
    }
}