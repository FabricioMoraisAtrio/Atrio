# Análise Concorrente (Portal do PEI) × Átrio + Roadmap de Produto

> Documento de estratégia de produto. Compara o **ecossistema de 7 módulos** anunciado pelo
> concorrente **Portal do PEI** com o que o **Átrio** já entrega, e define um roadmap
> priorizado por esforço × valor. Fonte do concorrente: descrição dos módulos na landing
> (fornecida pelo cliente). Fonte do Átrio: código do projeto (models, DocumentContentService,
> rotas, features construídas até jul/2026).

---

## 1. Cobertura módulo a módulo

Legenda: ✅ atendido · ◑ parcial (instrumento existe, falta camada) · ✗ não temos

| # | Módulo do concorrente | Átrio hoje | Status | O que falta para igualar/superar |
|---|---|---|---|---|
| 1 | **Estudo de Caso** — IA lê laudos/atas/avaliações e responde automaticamente às 40 perguntas do **Parecer 50 do MEC**, com LGPD (pseudonimização); relatório editável em minutos | Documento **Estudo de Caso** estruturado (`type=estudo_caso`): contexto familiar, histórico escolar, barreiras, potencialidades, encaminhamentos, equipe. Preenchimento **manual**. É pré-requisito do PAEE/PEI. | ◑ | **Automação por IA**: extrair dados de laudos/relatórios anexados e **auto-preencher**; mapear para as **40 perguntas do Parecer 50**; pseudonimização LGPD. É o hook mais forte deles. |
| 2 | **Avaliação Funcional** — instrumento próprio, 4 níveis de autonomia, baseado em observação (sem laudo), reaplicável no tempo | Temos os **blocos**: avaliação por meta com flag **autonomia / suporte / não executa / não observado** (PEI por matéria) + status por bimestre (`GoalProgress`: não atingiu / em progresso / atingiu) + **Linha do Tempo** (reaplicável ao longo do ano). | ◑ | **Empacotar** como instrumento dedicado "Avaliação Funcional" (tela própria, 4 níveis padronizados por habilidade escolar, reaplicável e comparável no tempo). Já somos ~70% do caminho — e **sem depender de laudo** (nosso hook validado). |
| 3 | **PEI e PAEE** — gera diretrizes de acessibilidade de um **repertório curado de práticas baseadas em evidência** (ensino explícito, análise de tarefas, CAA, DUA…), cada uma com indicação/benefício/aplicação | Temos **PEI** (objetivos curto/médio/longo, estratégias, critérios) e **PAEE** (perfil, recursos/estratégias, organização do atendimento, avaliação/monitoramento) como documentos separados + **Banco de Metas** reutilizável (`goal_templates`). | ◑ | **Biblioteca de práticas baseadas em evidência** (com indicação, benefício, como aplicar), sugeridas a partir do Estudo de Caso + Avaliação Funcional. Integra com o Banco de Metas já existente. |
| 4 | **Currículos adaptados** — objetivos/metas por disciplina cobrindo **toda a BNCC**, derivados do PEI/PAEE + currículos fora da BNCC (habilidades funcionais, comunicação) | **Metas por matéria** (`StudentAcademicGoal`: acadêmica / socioemocional / funcional) + Banco de Metas. Preenchimento manual. | ◑ | **Base curricular BNCC** (objetivos por ano/componente) para gerar/derivar metas adaptadas; trilha "fora da BNCC" para AEE. Hoje é livre, não ancorado na BNCC. |
| 5 | **Planos de aula** — aula pronta p/ o dia seguinte (sala regular + sala de recursos), reunindo PEI/PAEE/estudo de caso/currículo, com objetivos, adaptações e recursos | **Não temos** planos de aula. Temos **Adaptações para Prova** (módulo dedicado), mas não plano de aula diário. | ✗ | **Gerador de plano de aula** que combina o PEI/PAEE/currículo do aluno em uma aula com objetivos + estratégias de adaptação + recursos. Lacuna clara. |
| 6 | **Equipes** — integra multidisciplinar (direção, coordenação, AEE, saúde, psicologia) no mesmo plano, com acessos por papel | **Forte:** perfis built-in (admin/coordenador/orientador/professor) **+ perfis customizados** por escola (`SchoolRole`, permissões granulares) + equipe registrada nos documentos + **Reuniões/Atas** + **mural de Observações**. | ✅ | Já atendemos e superamos em granularidade. Possível extra: papéis explícitos "saúde/psicologia" e vínculo do profissional ao aluno (ver hook #6 da validação legal). |
| 7 | **Gestão de documentos** — centraliza PEI/PAEE/estudos/relatórios por aluno/turma/escola, templates editáveis com identidade da instituição, LGPD | **Forte:** multitenancy (`school_id`), export **PDF** (com logo/identidade da escola) e **Word**, **rastreabilidade LGPD** (`DocumentAccessLog` + Registro de Acessos), histórico por ano. | ✅ | Já atendemos. Diferencial nosso: log de acesso + nota de conformidade nos documentos. |

**Resumo:** dos 7 módulos, **2 já ganhamos** (Equipes, Gestão de Documentos), **4 são parciais** (Estudo de Caso, Avaliação Funcional, PEI/PAEE, Currículos) e **1 é lacuna** (Planos de Aula). A diferença central deles é **automação/IA + biblioteca curada + BNCC**; a nossa base documental e de perfis é igual ou superior.

---

## 2. Onde o Átrio já supera (diferenciais que NÃO estão na lista deles)

1. **Monitoramento contínuo de verdade** — **Linha do Tempo** (roadmap de evolução do aluno), **evolução de metas por bimestre** (gráfico + matriz) e **fechamento de bimestre** (congela resultado, trava, marca no roadmap). O concorrente cita "reaplicável", mas nós temos o ciclo completo de acompanhamento longitudinal.
2. **Profundidade jurídica** — 5 bases legais citadas com artigos (LBI, Decretos 12.686 e 12.773/2025, LDB, LGPD). Eles citam a lei de forma genérica.
3. **Rastreabilidade LGPD explícita** — `DocumentAccessLog` + tela "Registro de Acessos" + nota de conformidade nos PDFs.
4. **Adaptações para Prova** — módulo dedicado com exportação por turma.
5. **Multitenancy + gestão de módulos por escola** — cada escola liga/desliga módulos (Configurações → Módulos) e customiza perfis e terminologias.
6. **Jornada Alimentar / Seletividade** — nicho de inclusão que eles não cobrem.
7. **Fluxo colaborativo do PEI** — cada professor preenche a sua matéria; o sistema consolida automaticamente (PEI Consolidado).

---

## 3. Roadmap priorizado (esforço × valor)

Ordem sugerida — do maior retorno com menor esforço para o mais estratégico e caro:

1. **Avaliação Funcional como instrumento próprio** — *esforço baixo/médio, valor alto.*
   Já temos flags de autonomia + evolução por bimestre + Linha do Tempo. Empacotar numa tela dedicada com 4 níveis padronizados, reaplicável e comparável no tempo. Casa com o hook "**sem laudo**". Fecha o módulo #2 e ainda usa nossa força de monitoramento.
2. **Repertório de práticas baseadas em evidência** — *esforço médio, valor alto.*
   Biblioteca de estratégias (ensino explícito, análise de tarefas, CAA, DUA…) com indicação/benefício/aplicação, plugada no Banco de Metas e sugerida no PEI/PAEE. Fecha grande parte do módulo #3.
3. **Planos de aula** — *esforço médio, valor alto.*
   Gerador que combina PEI/PAEE/currículo do aluno em aula pronta (regular + sala de recursos). Fecha a única lacuna total (#5).
4. **BNCC nos currículos adaptados** — *esforço médio/alto (precisa da base BNCC), valor médio/alto.*
   Base de objetivos BNCC por ano/componente para derivar metas adaptadas + trilha AEE fora da BNCC. Fecha o módulo #4.
5. **IA no Estudo de Caso (Parecer 50 + extração de laudos)** — *esforço alto (LLM + pseudonimização LGPD), valor muito alto.*
   Aposta de médio prazo — é o diferencial mais forte deles. Extrair dados de documentos anexados e auto-preencher; mapear as 40 perguntas do Parecer 50. Requer integração de IA (usar modelos Claude), tratamento LGPD (pseudonimização) e curadoria do Parecer 50.

**Ganhos rápidos que não estão nos módulos deles (reforçar/vender):** Linha do Tempo/monitoramento contínuo, profundidade jurídica, LGPD/rastreabilidade, Adaptações para Prova — usar como argumento de venda na landing (ver plano da landing na Fase 1).

---

## 4. Posicionamento / recomendação

- **Não competir "no braço" de IA já.** Primeiro **empacotar o que já temos** (itens 1 e 2 do roadmap = ganhos rápidos que fecham 2 dos 4 módulos parciais).
- **Reforçar nossos diferenciais** (monitoramento contínuo + jurídico + LGPD) como venda — é onde já ganhamos e eles não têm.
- **IA no Estudo de Caso** como aposta estratégica de médio prazo (o hook mais forte deles). Quando entrarmos, entra com o nosso diferencial: **avaliação pedagógica primeiro, laudo opcional** + rastreabilidade LGPD.
- **Landing (Fase 1, em andamento):** funil comercial + CTA (WhatsApp `wa.me/5542988423965` / `suporte@atriosystem.com.br`) + hero com hook + tabela comparativa "gerador avulso vs. Átrio" + FAQ + SEO.

---

## Estado técnico relacionado (jul/2026)
- Já construído nesta fase: Linha do Tempo, evolução de metas por bimestre, fechamento de bimestre, Reuniões/Atas, gestão de módulos por escola, PDF do PEI com todas as matérias, perfil na topbar, sidebar responsiva. Tudo no GitHub (`main`).
- Landing: **Fase 1 concluída e commitada localmente** (funil + CTA WhatsApp/e-mail + comparativo + planos + FAQ + SEO). Fase 2 depende de conteúdo do cliente (depoimentos, autoridade/equipe).

---

## 5. Teardown completo do Portal do PEI (fornecido pelo cliente, jul/2026)

**Stack/sinais:** site de marketing tipo SPA (Next.js) com rotas amigáveis (`/plataforma/[modulo]`, `/professores`…); **produto logado separado em `/login.php` (PHP)**; assinaturas via **Hotmart** (`pay.hotmart.com`); **WhatsApp** (`wa.me/message/...`) recorrente + botão flutuante; rodapé cita LGPD + Decreto 12.686/2025.

**Navegação:** 3 dropdowns — *A Plataforma* (7 módulos, página própria cada), *Soluções* (Para professores, Escolas particulares, Contratação pública), *Institucional* (Nossa história, Fundadores, Nossa equipe) — + itens diretos: Planos, Depoimentos, Blog, Contato, Entrar, "Ver planos".

**Home:** hero "Todas as ferramentas… em um único lugar" → jornada em 5 etapas → autoridade (fundadores/especialistas com citações) → depoimentos por cargo/estado → bloco "problema" → comparativo avulso × ecossistema → 8 recursos → segmentação por perfil → resumo de planos → FAQ (6) → CTA duplo.

**Páginas de módulo (7):** padrão repetido — hero + "o problema" + "como funciona" em **3 passos** + "benefícios" (**4 itens**) + "parte do ecossistema" (linka 3 módulos relacionados) + CTA duplo. **Todas com placeholder "Captura da plataforma — em breve" (sem prints reais do produto).**

**Soluções por público:** `/professores` = self-serve (autonomia, "PEI em ~20 min", checkout Hotmart). `/escolas-particulares` e `/contratacao-publica` = venda consultiva (formulário de lead: nome, e-mail, telefone, escola/órgão, nº alunos/professores, mensagem). **Contratação pública** argumenta metodologia autoral → **inexigibilidade de licitação (Lei 14.133/2021)** + fornece documentos de suporte.

**Planos (individuais):** toggle mensal/anual (−25% anual). **Essencial:** 10 alunos — R$150/mês (mensal) ou R$120/mês (anual). **Profissional:** 25 alunos (35 no anual) — R$320/mês (mensal) ou R$240/mês (anual) + bônus (alunos extras, curso "ABA na Escola"). Escolas/órgãos → formulário consultivo (sem checkout).

**Institucional/prova social:** fundadores **nomeados e credenciados** (Prof. Leandro Rodrigues / Instituto Itard; Dr. Thiago Castro, pediatra ref. em TEA). `/depoimentos` com **métricas** (687 profissionais ativos, 3.386 PEIs/PAEEs gerados, 716 alunos) + depoimentos por cargo/estado (**sem nome/foto por ora**). **Blog** ativo (Institucional/Gestão/Metodologia). `/contato` com WhatsApp + e-mail + formulário. `/login.php` com banner de cookies.

## 6. Insights estratégicos afiados por este teardown

1. **Maior vantagem nossa: temos produto REAL e mostrável.** Eles descrevem 7 módulos mas **não mostram nenhuma tela** ("em breve"). **Prints/GIFs/demo ao vivo do Átrio funcionando** viram credibilidade instantânea que eles não conseguem igualar hoje. → Colocar screenshots reais na landing (Fase 2 prioritária, e não depende de terceiros).
2. **Referência de preço (benchmark) obtida.** Individual R$120–150 (10 alunos) / R$240–320 (25 alunos), Hotmart, mensal/anual. Escola = consultivo. → Base concreta para definir os **nossos** planos (hoje "sob consulta"). Decisão de precificação do cliente.
3. **Funil de duas trilhas confirmado:** self-serve (Hotmart) p/ professor + consultivo (formulário) p/ escola/rede. Nossa landing já segmenta professor × escola/rede; possível evoluir p/ **auto-contratação do professor** e **formulário de lead** p/ escola.
4. **Autoridade nomeada é o padrão deles (e nosso maior gap).** Precisamos de **rostos + credenciais** (pedagogo/especialista/jurídico) na landing. Sem isso, perdemos em confiança.
5. **Métricas como prova social.** Eles exibem números. Precisamos dos **nossos números reais** (escolas, PEIs gerados, alunos) assim que houver base.
6. **Canal B2G via inexigibilidade (Lei 14.133/2021).** Argumento de metodologia autoral p/ contratação pública sem licitação. **Casa com a nossa força jurídica** — potencial página `/contratacao-publica` com esse enquadramento + docs de suporte.
7. **Conteúdo/SEO (blog + institucional).** Jogo de médio prazo p/ autoridade e busca orgânica.
8. **Padrão de "página de módulo" reutilizável.** Se formos criar páginas de recurso/produto, usar **um componente/partial parametrizado** (hero, problema, 3 passos, 4 benefícios, ecossistema-3-links, CTA duplo) — não N páginas soltas.

**Reordenação sugerida do roadmap à luz disto:** subir **"mostrar o produto real na landing" (prints/demo)** para o topo dos ganhos rápidos (é grátis, imediato e é onde 100% ganhamos deles). Manter o roadmap de produto (Avaliação Funcional → práticas → planos de aula → BNCC → IA no Estudo de Caso).
