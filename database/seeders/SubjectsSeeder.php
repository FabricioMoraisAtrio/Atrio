<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $schools = \App\Models\School::all();

        $materias = [
            [
                'name' => 'Língua Portuguesa', 'slug' => 'portugues',
                'label_responsavel' => 'Prof. Português', 'tipo' => 'disciplina', 'ordem' => 1,
                'metas' => [
                    ['meta' => 'Ler e compreender textos diversos',                     'categoria' => 'academica'],
                    ['meta' => 'Estruturar parágrafos de forma compreensível',           'categoria' => 'academica'],
                    ['meta' => 'Expressar-se oralmente de forma clara e funcional',     'categoria' => 'academica'],
                ],
            ],
            [
                'name' => 'Matemática', 'slug' => 'matematica',
                'label_responsavel' => 'Prof. Matemática', 'tipo' => 'disciplina', 'ordem' => 2,
                'metas' => [
                    ['meta' => 'Aplicar conhecimentos matemáticos em situações-problema', 'categoria' => 'academica'],
                    ['meta' => 'Estabelecer relações numéricas e lógico-matemáticas',     'categoria' => 'academica'],
                ],
            ],
            [
                'name' => 'História', 'slug' => 'historia',
                'label_responsavel' => 'Prof. História', 'tipo' => 'disciplina', 'ordem' => 3,
                'metas' => [
                    ['meta' => 'Estabelecer relação entre causa e efeito',               'categoria' => 'academica'],
                    ['meta' => 'Compreender contextos históricos e sociais',             'categoria' => 'academica'],
                ],
            ],
            [
                'name' => 'Geografia', 'slug' => 'geografia',
                'label_responsavel' => 'Prof. Geografia', 'tipo' => 'disciplina', 'ordem' => 4,
                'metas' => [
                    ['meta' => 'Trabalhar com linhas do tempo, escalas e noções de sociedade', 'categoria' => 'academica'],
                    ['meta' => 'Compreender a relação entre espaço e sociedade',               'categoria' => 'academica'],
                ],
            ],
            [
                'name' => 'Ciências', 'slug' => 'ciencias',
                'label_responsavel' => 'Prof. Ciências', 'tipo' => 'disciplina', 'ordem' => 5,
                'metas' => [
                    ['meta' => 'Demonstrar curiosidade e interesse intelectual',                    'categoria' => 'academica'],
                    ['meta' => 'Relacionar conceitos científicos a situações cotidianas',           'categoria' => 'academica'],
                ],
            ],
            [
                'name' => 'Arte', 'slug' => 'arte',
                'label_responsavel' => 'Prof. Arte', 'tipo' => 'disciplina', 'ordem' => 6,
                'metas' => [
                    ['meta' => 'Participar ativamente das atividades propostas',        'categoria' => 'academica'],
                    ['meta' => 'Expressar-se por meio de diferentes linguagens artísticas', 'categoria' => 'academica'],
                ],
            ],
            [
                'name' => 'Educação Física', 'slug' => 'ed-fisica',
                'label_responsavel' => 'Prof. Ed. Física', 'tipo' => 'disciplina', 'ordem' => 7,
                'metas' => [
                    ['meta' => 'Participar ativamente das atividades propostas',        'categoria' => 'academica'],
                    ['meta' => 'Demonstrar coordenação motora e equilíbrio',            'categoria' => 'funcional'],
                ],
            ],
            [
                'name' => 'Ensino Religioso / Interculturalidade', 'slug' => 'ensino-religioso',
                'label_responsavel' => 'Prof. E.Rel./Inter.', 'tipo' => 'disciplina', 'ordem' => 8,
                'metas' => [
                    ['meta' => 'Participar ativamente das atividades propostas',        'categoria' => 'academica'],
                    ['meta' => 'Demonstrar respeito à diversidade cultural e religiosa', 'categoria' => 'socioemocional'],
                ],
            ],
            [
                'name' => 'Professor Regente', 'slug' => 'regente',
                'label_responsavel' => 'Prof. Regente', 'tipo' => 'regente', 'ordem' => 9,
                'metas' => [
                    ['meta' => 'Cumprir as regras e combinados da turma',                              'categoria' => 'socioemocional'],
                    ['meta' => 'Reconhecer as próprias emoções, pontos fortes e limitações',           'categoria' => 'socioemocional'],
                    ['meta' => 'Demonstrar senso de responsabilidade',                                 'categoria' => 'socioemocional'],
                    ['meta' => 'Demonstrar escuta atenta',                                             'categoria' => 'socioemocional'],
                    ['meta' => 'Relacionar-se com seus pares nas situações escolares cotidianas',      'categoria' => 'socioemocional'],
                    ['meta' => 'Demonstrar autonomia na realização das tarefas',                       'categoria' => 'funcional'],
                    ['meta' => 'Demonstrar autocontrole em situações escolares',                       'categoria' => 'funcional'],
                    ['meta' => 'Demonstrar organização pessoal',                                       'categoria' => 'funcional'],
                    ['meta' => 'Demonstrar atenção e concentração',                                    'categoria' => 'funcional'],
                    ['meta' => 'Demonstrar memória auditiva e visual',                                 'categoria' => 'funcional'],
                ],
            ],
        ];

        foreach ($schools as $school) {
            foreach ($materias as $materia) {
                $subject = \App\Models\Subject::updateOrCreate(
                    ['school_id' => $school->id, 'slug' => $materia['slug']],
                    [
                        'name'              => $materia['name'],
                        'label_responsavel' => $materia['label_responsavel'],
                        'tipo'              => $materia['tipo'],
                        'ordem'             => $materia['ordem'],
                    ]
                );

                foreach ($materia['metas'] as $j => $item) {
                    \App\Models\SubjectInventoryItem::updateOrCreate(
                        ['school_id' => $school->id, 'subject_id' => $subject->id, 'meta' => $item['meta']],
                        ['categoria' => $item['categoria'], 'ordem' => $j + 1]
                    );
                }
            }
        }
    }
}
