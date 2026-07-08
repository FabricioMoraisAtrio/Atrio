<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\GoalTemplate;
use Illuminate\Http\Request;

class GoalTemplateController extends Controller
{
    /**
     * Banco de metas reutilizáveis da escola — sugestões por categoria que
     * abastecem o cadastro de metas de cada aluno (via datalist).
     * Restrito por pei.metas_gerenciar.
     */
    public function edit()
    {
        $templates = GoalTemplate::orderBy('ordem')->orderBy('id')->get()->groupBy('categoria');

        return view('secretaria.metas.banco', compact('templates'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'metas'     => 'nullable|array',
            'metas.*'   => 'nullable|array',
            'metas.*.*' => 'nullable|string|max:500',
        ]);

        $schoolId = session('school_id');
        $entrada  = (array) $request->input('metas', []);

        foreach (array_keys(GoalTemplate::CATEGORIES) as $categoria) {
            // Reescreve a categoria por completo (mais simples e previsível).
            GoalTemplate::where('categoria', $categoria)->delete();

            $ordem = 1;
            foreach ((array) ($entrada[$categoria] ?? []) as $texto) {
                $texto = trim((string) $texto);
                if ($texto === '') {
                    continue;
                }

                GoalTemplate::create([
                    'school_id' => $schoolId,
                    'categoria' => $categoria,
                    'texto'     => $texto,
                    'ordem'     => $ordem++,
                ]);
            }
        }

        return redirect()->route('secretaria.metas.banco.edit')
            ->with('success', 'Banco de metas atualizado.');
    }
}
