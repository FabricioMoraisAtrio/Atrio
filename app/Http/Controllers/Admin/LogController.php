<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\AdminUser;
use App\Models\DocumentAccessLog;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $fonte   = $request->get('fonte') === 'painel' ? 'painel' : 'escolas';
        $schools = School::orderBy('name')->pluck('name', 'id');

        // ── Atividade do painel (superadmin) ──
        if ($fonte === 'painel') {
            $q = AdminLog::with('admin');
            if ($request->filled('school_id')) {
                $q->where('school_id', $request->school_id);
            }
            if ($request->filled('action')) {
                $q->where('action', $request->action);
            }
            if ($request->filled('from')) {
                $q->whereDate('created_at', '>=', $request->from);
            }
            if ($request->filled('to')) {
                $q->whereDate('created_at', '<=', $request->to);
            }

            $adminLogs    = $q->orderByDesc('created_at')->paginate(30)->withQueryString();
            $actionLabels = AdminLog::actionLabels();

            return view('admin.logs.index', compact('fonte', 'schools', 'adminLogs', 'actionLabels'));
        }

        // ── Acessos das escolas (DocumentAccessLog, visão global) ──
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

        $logs    = $q->orderByDesc('accessed_at')->paginate(30)->withQueryString();
        $users   = User::withoutGlobalScopes()->pluck('name', 'id');
        $actions = DocumentAccessLog::withoutGlobalScopes()->distinct()->orderBy('action')->pluck('action');

        return view('admin.logs.index', compact('fonte', 'logs', 'schools', 'users', 'actions'));
    }
}
