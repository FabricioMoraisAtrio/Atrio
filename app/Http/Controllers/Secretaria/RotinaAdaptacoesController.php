<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class RotinaAdaptacoesController extends Controller
{
    const CATEGORIAS = [
        'suporte_mediacao' => [
            'label' => 'Suporte e Mediação',
            'itens' => [
                'Apoio de escriba',
                'Intérprete de Libras',
                'Leitura em voz alta pelo professor',
            ],
        ],
        'prova_adaptada' => [
            'label' => 'Prova Adaptada',
            'itens' => [
                'Tempo extra na prova',
                'Sem limite de tempo',
                'Redução de questões',
                'Questões objetivas (sem dissertativas)',
                'Avaliação oral',
            ],
        ],
        'formatacao_diferenciada' => [
            'label' => 'Formatação Diferenciada',
            'itens' => [
                'Prova com fonte ampliada',
                'Prova com imagens de apoio',
                'Prova em braille',
                'Prova digitalizada',
            ],
        ],
        'aplicacao_diferenciada' => [
            'label' => 'Aplicação Diferenciada',
            'itens' => [
                'Sala separada',
                'Gravação de resposta (áudio)',
            ],
        ],
        'material_adaptado' => [
            'label' => 'Material Adaptado',
            'itens' => [
                'Material concreto/manipulativo',
                'Texto de apoio',
                'Uso de calculadora',
                'Adaptação de conteúdo',
            ],
        ],
    ];

    public function __invoke(Request $request)
    {
        $filtroTurma   = $request->input('turma');
        $filtroPublico = $request->input('publico');
        $filtroGrupo   = $request->input('grupo');   // chave do config/transtornos
        $filtroDoc     = $request->input('doc');     // pei | ec | paee
        $filtroLaudo   = $request->input('laudo');   // sim | nao
        $transtornos   = config('transtornos');

        // Valida grupo
        if ($filtroGrupo && !array_key_exists($filtroGrupo, $transtornos)) {
            $filtroGrupo = null;
        }

        // Filtros por categoria de adaptação (um por categoria)
        $filtrosAdap = [];
        foreach (array_keys(self::CATEGORIAS) as $cat) {
            $val = $request->input($cat);
            if ($val) {
                $filtrosAdap[$cat] = $val;
            }
        }

        $query = Student::with([
            'schoolClasses',
            'documents' => fn($q) => $q->whereIn('type', ['pei', 'estudo_caso', 'paee'])->where('year', date('Y')),
            'laudos',
        ]);

        if ($filtroTurma) {
            $query->whereHas('schoolClasses', fn($q) => $q->where('school_classes.id', $filtroTurma));
        }

        if ($filtroPublico === 'sim') {
            $query->where('is_atypical', true);
        } elseif ($filtroPublico === 'nao') {
            $query->where('is_atypical', false);
        }

        if ($filtroGrupo) {
            $query->where($filtroGrupo, true);
        }

        if ($filtroDoc) {
            $tipo = $filtroDoc === 'ec' ? 'estudo_caso' : $filtroDoc;
            $query->whereHas('documents', fn($q) => $q->where('type', $tipo)->where('year', date('Y')));
        }

        if ($filtroLaudo === 'sim') {
            $query->has('laudos');
        } elseif ($filtroLaudo === 'nao') {
            $query->doesntHave('laudos');
        }

        $alunos = $query->orderBy('name')->get();

        // Filtros de adaptação em PHP após carregamento
        foreach ($filtrosAdap as $cat => $item) {
            $alunos = $alunos->filter(fn($a) => in_array($item, $this->getAdaptacoes($a)));
        }

        $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get();

        return view('secretaria.rotinas.adaptacoes_prova', [
            'alunos'       => $alunos,
            'turmas'       => $turmas,
            'categorias'   => self::CATEGORIAS,
            'transtornos'  => $transtornos,
            'filtroTurma'  => $filtroTurma,
            'filtroPublico' => $filtroPublico,
            'filtroGrupo'  => $filtroGrupo,
            'filtroDoc'    => $filtroDoc,
            'filtroLaudo'  => $filtroLaudo,
            'filtrosAdap'  => $filtrosAdap,
        ]);
    }

    private function getAdaptacoes(Student $aluno): array
    {
        $adaptacoes = [];
        foreach ($aluno->documents->where('type', 'pei') as $doc) {
            $content = $doc->content ?? [];
            $itens   = $content['adaptacoes'] ?? [];
            if (is_string($itens)) {
                $itens = array_filter(array_map('trim', explode(',', $itens)));
            }
            $adaptacoes = array_merge($adaptacoes, (array) $itens);
        }
        return array_values(array_unique($adaptacoes));
    }
}
