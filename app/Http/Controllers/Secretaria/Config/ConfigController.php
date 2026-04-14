<?php

namespace App\Http\Controllers\Secretaria\Config;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolSetting;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    private function escola(): School
    {
        return School::findOrFail(session('school_id'));
    }

    public function index()
    {
        $escola    = $this->escola();
        $settings  = SchoolSetting::getAllForSchool($escola->id);
        $subjects  = Subject::withCount('inventoryItems')->orderBy('ordem')->get();

        return view('secretaria.config.index', compact('escola', 'settings', 'subjects'));
    }

    public function updateEscola(Request $request)
    {
        $escola = $this->escola();

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'theme_color' => 'nullable|string|max:20',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($escola->logo) {
                Storage::disk('public')->delete($escola->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $escola->update(array_filter($data, fn($v) => $v !== null));

        return redirect()->route('secretaria.config.index')
            ->with('success', 'Dados da escola atualizados.');
    }

    public function updateTerminologias(Request $request)
    {
        $schoolId = session('school_id');

        $terms = [
            'aluno', 'alunos', 'turma', 'turmas',
            'laudo', 'laudos', 'professor', 'professores',
            'coordenador', 'orientador', 'documento', 'documentos',
            'publico_alvo', 'nao_publico_alvo',
        ];

        foreach ($terms as $term) {
            $value = $request->input("term_{$term}", '');
            if ($value !== '') {
                SchoolSetting::setValue($schoolId, "term_{$term}", trim($value));
            }
        }

        return redirect()->route('secretaria.config.index', ['tab' => 'terminologias'])
            ->with('success', 'Terminologias atualizadas.');
    }
}
