<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentAccessLog;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Visão global: sem o SchoolScope (que filtra por session school_id).
        $q = DocumentAccessLog::withoutGlobalScopes();

        if ($request->filled('school_id')) {
            $q->where('school_id', $request->school_id);
        }
        if ($request->filled('action')) {
            $q->where('action', $request->action);
        }
        if ($request->filled('from')) {
            $q->whereDate('accessed_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('accessed_at', '<=', $request->to);
        }

        $logs = $q->orderByDesc('accessed_at')->paginate(30)->withQueryString();

        $schools = School::orderBy('name')->pluck('name', 'id');
        $users   = User::withoutGlobalScopes()->pluck('name', 'id');
        $actions = DocumentAccessLog::withoutGlobalScopes()
            ->distinct()->orderBy('action')->pluck('action');

        return view('admin.logs.index', compact('logs', 'schools', 'users', 'actions'));
    }
}
