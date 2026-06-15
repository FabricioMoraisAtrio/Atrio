<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\DocumentAccessLog;
use App\Models\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = session('school_id');

        $query = DocumentAccessLog::query()
            ->with(['document', 'user'])
            ->where('school_id', $schoolId)
            ->orderByDesc('accessed_at');

        if ($request->filled('usuario')) {
            $query->where('user_id', $request->input('usuario'));
        }

        if ($request->filled('acao')) {
            $query->where('action', $request->input('acao'));
        }

        if ($request->filled('de')) {
            $query->whereDate('accessed_at', '>=', $request->input('de'));
        }

        if ($request->filled('ate')) {
            $query->whereDate('accessed_at', '<=', $request->input('ate'));
        }

        if ($request->filled('aluno')) {
            $query->where('student_name', 'like', '%' . $request->input('aluno') . '%');
        }

        $logs = $query->paginate(50)->withQueryString();

        $usuarios = User::where('school_id', $schoolId)->orderBy('name')->get(['id', 'name']);

        return view('secretaria.logs.index', compact('logs', 'usuarios'));
    }
}
