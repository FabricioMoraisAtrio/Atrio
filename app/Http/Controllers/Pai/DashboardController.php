<?php

namespace App\Http\Controllers\Pai;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $filhos = auth()->user()->children()
            ->with(['documents' => function ($q) {
                $q->where('year', date('Y'))
                  ->orderBy('type');
            }, 'schoolClasses'])
            ->get();

        return view('pai.dashboard', compact('filhos'));
    }
}