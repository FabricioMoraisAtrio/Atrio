<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Announcement;
use App\Models\School;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('school')->latest()->get();
        $schools       = School::orderBy('name')->get(['id', 'name']);

        return view('admin.comunicados.index', compact('announcements', 'schools'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['admin_user_id'] = auth('admin')->id();
        $a = Announcement::create($data);
        AdminLog::record('comunicado_criado', "Comunicado \"{$a->title}\" publicado", $a->school_id);

        return back()->with('success', 'Comunicado publicado.');
    }

    public function update(Request $request, Announcement $comunicado)
    {
        $comunicado->update($this->validated($request));
        AdminLog::record('comunicado_editado', "Comunicado \"{$comunicado->title}\" editado", $comunicado->school_id);

        return back()->with('success', 'Comunicado atualizado.');
    }

    public function toggle(Announcement $comunicado)
    {
        $comunicado->update(['active' => ! $comunicado->active]);

        return back()->with('success', $comunicado->active ? 'Comunicado ativado.' : 'Comunicado desativado.');
    }

    public function destroy(Announcement $comunicado)
    {
        AdminLog::record('comunicado_removido', "Comunicado \"{$comunicado->title}\" removido", $comunicado->school_id);
        $comunicado->delete();

        return back()->with('success', 'Comunicado removido.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'body'      => 'required|string',
            'level'     => 'required|in:info,warning',
            'audience'  => 'required|in:all,school',
            'school_id' => 'nullable|required_if:audience,school|exists:schools,id',
            'active'    => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at'   => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data['active']    = $request->boolean('active');
        $data['school_id'] = $data['audience'] === 'school' ? $data['school_id'] : null;

        return $data;
    }
}
