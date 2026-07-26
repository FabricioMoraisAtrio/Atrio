<?php

namespace App\Support;

/**
 * Fonte única dos módulos exibidos no site institucional (marketing).
 * Usada pelas páginas /plataforma e /plataforma/{modulo}, pelo mega-menu e pelo rodapé.
 * Mantém a cópia num só lugar — mudou aqui, muda em todo o site.
 */
class Modulos
{
    /** @return array<string,array> slug => dados do módulo (na ordem de exibição) */
    public static function all(): array
    {
        return [
            'estudo-de-caso' => [
                'nav'     => 'Estudo de Caso',
                'title'   => 'Estudo de Caso',
                'law'     => 'Decreto 12.686/2025 · Avaliação pedagógica inicial',
                'dot'     => '#004B8D',
                'tagline' => 'A avaliação pedagógica que abre o processo de AEE.',
                'desc'    => 'Feita pela própria equipe escolar, caracteriza o estudante e fundamenta o PAEE e o PEI — sem depender de laudo médico para começar.',
                'items'   => [
                    'Histórico escolar e contexto familiar',
                    'Barreiras identificadas e potencialidades',
                    'Diagnóstico pedagógico e objetivos de aprendizagem',
                    'Pré-requisito para liberar o PAEE',
                ],
                'icon'    => '<circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
            ],
            'paee' => [
                'nav'     => 'PAEE',
                'title'   => 'PAEE — Plano de Atendimento Educacional Especializado',
                'law'     => 'Decreto 12.686/2025 · Planejamento do AEE',
                'dot'     => '#009C8C',
                'tagline' => 'O planejamento do profissional de AEE, do objetivo ao critério de avaliação.',
                'desc'    => 'Reúne objetivos do atendimento, recursos e estratégias, organização e critérios de avaliação. No Átrio, só é liberado após o Estudo de Caso.',
                'items'   => [
                    'Perfil do estudante e necessidades identificadas',
                    'Objetivos geral e específicos do AEE',
                    'Tecnologias assistivas, adaptações e metodologias',
                    'Frequência, local e critérios de avaliação',
                ],
                'icon'    => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
            ],
            'pei' => [
                'nav'     => 'PEI Consolidado',
                'title'   => 'PEI — Plano Educacional Individualizado',
                'law'     => 'LBI Art. 28 · Decretos 12.686 e 12.773/2025',
                'dot'     => '#7C3AED',
                'tagline' => 'Preenchido por matéria, consolidado automaticamente num documento único.',
                'desc'    => 'Cada professor cuida da sua disciplina e o Átrio consolida tudo num documento final, com a equipe responsável registrada.',
                'items'   => [
                    'Metas de curto, médio e longo prazo',
                    'Inventário de habilidades por disciplina e por professor',
                    'Adaptações curriculares e estratégias pedagógicas',
                    'Documento consolidado, exportável em PDF e Word',
                ],
                'icon'    => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
            ],
            'linha-do-tempo' => [
                'nav'     => 'Linha do Tempo',
                'title'   => 'Linha do Tempo & Evolução por Bimestre',
                'law'     => 'Acompanhamento contínuo · diferencial do Átrio',
                'dot'     => '#0062BC',
                'tagline' => 'Vai além do documento: acompanha a evolução real do aluno ao longo do ano.',
                'desc'    => 'Metas, reuniões, laudos e observações numa linha do tempo, com abertura e fechamento de bimestre que congela e registra o resultado.',
                'items'   => [
                    'Evolução das metas do PEI por bimestre',
                    'Fechamento de bimestre que congela e registra o resultado',
                    'Marcos automáticos: metas, reuniões, laudos e observações',
                    'Reabertura controlada pela coordenação',
                ],
                'icon'    => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
            ],
            'adaptacoes' => [
                'nav'     => 'Adaptações para Prova',
                'title'   => 'Adaptações para Avaliação',
                'law'     => 'LBI Art. 28, XIII · Decreto 12.686/2025',
                'dot'     => '#C77A00',
                'tagline' => 'Registro individualizado das adaptações de prova de cada estudante.',
                'desc'    => 'Para que as adaptações sejam aplicadas de forma consistente em todas as avaliações do período, e exportadas por turma.',
                'items'   => [
                    'Tempo ampliado, sala separada, ledor, transcritor',
                    'Registro por estudante e por período letivo',
                    'Exportação por turma para aplicação nas provas',
                    'Histórico completo de adaptações',
                ],
                'icon'    => '<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>',
            ],
            'laudos' => [
                'nav'     => 'Laudos & Observações',
                'title'   => 'Laudos & Observações',
                'law'     => 'LGPD · dados sensíveis do estudante',
                'dot'     => '#C0392B',
                'tagline' => 'Os laudos e o mural de observações do aluno, com rastreabilidade.',
                'desc'    => 'Organiza laudos médicos e psicológicos e as observações pedagógicas do aluno, com controle de acesso por perfil.',
                'items'   => [
                    'Upload de laudos com tipo e data',
                    'Identificação por CID e público-alvo da educação especial',
                    'Mural de observações por aluno',
                    'Observação crítica dispara notificação à equipe',
                ],
                'icon'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            ],
            'documentos' => [
                'nav'     => 'Gestão de Documentos',
                'title'   => 'Gestão de Documentos',
                'law'     => 'LGPD · rastreabilidade e exportação',
                'dot'     => '#3B5BDB',
                'tagline' => 'Todos os documentos num só lugar, exportáveis e rastreáveis.',
                'desc'    => 'Estudo de Caso, PAEE e PEI centralizados por aluno e por ano, com exportação em PDF e Word e registro de cada acesso.',
                'items'   => [
                    'Um documento por tipo, aluno e ano letivo',
                    'Exportação em PDF e Word com identificação de autoria',
                    'Registro de acesso a cada documento (data e usuário)',
                    'Nota de conformidade LGPD nos documentos gerados',
                ],
                'icon'    => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><polyline points="9 15 11 17 15 13"/>',
            ],
            'perfis' => [
                'nav'     => 'Perfis & permissões',
                'title'   => 'Perfis & Permissões',
                'law'     => 'LDB Art. 59 · atuação colaborativa',
                'dot'     => '#16A34A',
                'tagline' => 'Cada profissional vê só o que precisa — com papéis e permissões próprios.',
                'desc'    => 'Administração, coordenação, orientação e professores, além de papéis customizados por escola. Cada escola habilita os módulos que usa.',
                'items'   => [
                    'Papéis prontos: admin, coordenação, orientação e professor',
                    'Papéis customizados por escola, com permissões próprias',
                    'Cada professor preenche o PEI da sua disciplina',
                    'Módulos habilitados por escola',
                ],
                'icon'    => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/>',
            ],
        ];
    }

    /** Retorna um módulo com o slug embutido, ou null se não existir. */
    public static function find(string $slug): ?array
    {
        $all = self::all();

        return isset($all[$slug]) ? array_merge($all[$slug], ['slug' => $slug]) : null;
    }
}
