<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\School;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $schools = School::withCount(['users', 'students', 'schoolClasses'])
            ->latest()
            ->get();

        $stats = [
            'total_schools'   => $schools->count(),
            'active_schools'  => $schools->where('is_active', true)->count(),
            'total_students'  => $schools->sum('students_count'),
            'total_documents' => Document::count(),
        ];

        return view('admin.dashboard', compact('schools', 'stats'));
    }
}