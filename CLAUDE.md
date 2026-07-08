# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Visão geral

Sistema Átrio — SaaS multi-escola (multitenancy) para gestão de inclusão escolar (PEI, PAEE, Estudo de Caso, Laudos, Observações, Jornada Alimentar/Seletividade, Adaptações para Prova). Stack: Laravel 12 (PHP 8.2) + Blade + Tailwind CSS 4 + Vite, SQLite em dev.

## Comandos

```bash
# Ambiente de desenvolvimento completo (server + queue + logs + vite)
composer dev

# Apenas assets
npm run dev
npm run build

# Testes
php artisan test
php artisan test --filter NomeDoTeste     # ex: --filter DocumentoTest
composer test                              # limpa config antes de testar

# Banco de dados
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed

# Fila (driver database)
php artisan queue:work --sleep=3 --tries=3

# Notificações diárias (rodam via scheduler às 07:00, ou via /cron/notificacoes com token)
php artisan atrio:notificacoes-diarias

# Code style (preset laravel, regra no_unused_imports desabilitada)
vendor/bin/pint
```

Ambiente de teste (`phpunit.xml`) usa SQLite em memória, fila `sync` e e-mail `array`.

## Arquitetura

### Multitenancy
Toda tabela de dados tem `school_id`. O `App\Scopes\SchoolScope` (global scope) filtra automaticamente por `session('school_id')` — aplicado em `School`, `SchoolClass`, `Student`, `Document`, `PeiSection`, `Subject`, `SubjectInventoryItem`, `Laudo`, `Observation`, `DocumentAccessLog`. **`User` não usa o scope** (conflita com queries internas do Spatie Permission). `session('school_id')` é gravado no login.

### Roles, permissões e módulos
- Permissões/roles via `spatie/laravel-permission`, seedadas em `database/seeders/RolesAndPermissionsSeeder.php`.
- Roles built-in: `admin`, `coordenador`, `orientador` (acesso ao "portal", antigo perfil secretaria — renomeado de `secretaria` para `admin` na migration `2026_04_04_*`) e `professor`.
- Cada escola pode criar **roles customizados** via `App\Models\SchoolRole`, mapeados para roles Spatie com prefixo `s{school_id}_` e permissões próprias (campo `permissions` JSON).
- Rotas de portal usam middleware `can:<permissao>` (ex.: `can:alunos.ver`, `can:documentos.ver_todos`) — não checam role diretamente.
- `EnsureSchoolMember` (`school.member`) libera acesso ao portal para roles built-in (`admin`/`coordenador`/`orientador`) ou para qualquer role `s{school_id}_*`.
- `EnsureSchoolHasModule` (`school.module:<chave>`) bloqueia rotas se o módulo não estiver habilitado em `School.modules` (JSON array; `null` = todos habilitados). Módulos disponíveis: `painel`, `alunos`, `documentos`, `turmas`, `adaptacoes`, `materias`, `usuarios`, `configuracoes`, `seletividade` (Jornada Alimentar) — ver `School::availableModules()`.
- `EnsureSchoolIsActive` (`school.active`) faz logout se a escola estiver inativa ou com plano vencido.
- Guard separado `admin` (`AdminUser`) para o painel SaaS em `/superadmin`, com middleware `admin.auth`.

### Rotas
- `routes/web.php`: rotas públicas (login, reset de senha, landing, termos) + grupo `auth + school.active` que carrega:
  - `routes/secretaria.php` com prefixo `/portal`, nome `secretaria.*`, middleware `school.member` (apesar do nome, hoje é o portal de admin/coordenador/orientador + roles customizados);
  - `routes/professor.php` com prefixo `/professor`, nome `professor.*`, middleware `role:professor`.
- Dentro de `secretaria.php`, cada grupo de recursos combina `school.module:<modulo>` + `can:<permissao>`.
- `/superadmin/*` é guard `admin`, fora do middleware de auth padrão.

### Terminologia customizável
`term('chave')` (em `app/Helpers/helpers.php`) retorna o termo customizado da escola (`SchoolSetting`, prefixo `term_*`) ou um default do sistema. Usado nas views para permitir que cada escola renomeie conceitos (ex.: "Aluno" → outro termo).

### Módulo de Documentos / PEI
- `Document` (`school_id`, `student_id`, `author_id`, `type`, `year`, `status`, `content` JSON) cobre os tipos `estudo_caso` e `paee`. Regra: Estudo de Caso é pré-requisito para PAEE; um documento por tipo/aluno/ano.
- `App\Services\DocumentContentService::buildContent(type, request)` centraliza a montagem do `content` a partir do request (evita duplicação entre controllers).
- **PEI não fica no `Document`**: é por matéria, no model `PeiSection` (`student_id`, `subject_id`, `author_id`, `year`, `content` JSON). Cada professor edita a seção da sua matéria (`Professor\DocumentController@editPei/updatePei`, `DocumentContentService::mergePeiSubject`). A secretaria consolida via `Secretaria\PeiConsolidadoController`.
- `Subject` (matérias da escola, gerenciadas pelo superadmin) tem `inventoryItems` (`SubjectInventoryItem`, categorias) e `peiSections`.
- Exportação: PDF via `barryvdh/laravel-dompdf`, Word via `phpoffice/phpword` (`DocumentWordController` em cada perfil).
- Cache `pendentes_count_{school_id}` (TTL 5min) é invalidado nos eventos `saved`/`deleted` do `Document`.

### Outros módulos relevantes
- `Laudo`: laudos médicos/psicológicos anexados ao aluno (`tipo`, `arquivo`, `data_laudo`).
- `Observation`: mural de observações por aluno; urgência `critico` dispara `ObservacaoCriticaNotification`.
- `StudentFoodItem` (Jornada Alimentar / Seletividade): aceitação alimentar por categoria (`CATEGORIES`) e status (`aceita`/`tolera`/`recusa`), gerido por `SeletividadeController`.
- `Student`: muitos flags `cid_*` booleanos; `PUBLICO_ALVO_FIELDS` define quais condições enquadram o aluno como Público Alvo da Educação Especial (`is_publico_alvo`).

### Notificações
Todas implementam `ShouldQueue`. Tipos: `DocumentosPendentesNotification`, `ObservacaoCriticaNotification`, `NovoDocumentoPaiNotification`, `PlanoVencendoNotification`. Preferências por usuário: `notify_document_pending`, `notify_plan_expiring`.

## Documentação existente (cuidado com desatualização)

`ARQUITETURA_E_ROTAS.md`, `DOCUMENTACAO_TECNICA.md`, `GUIA_IMPLANTACAO.md` e `MANUAL_USUARIO.md` são úteis para visão geral e deploy, mas descrevem uma versão anterior do sistema (role `secretaria`/`pai`, rotas `/secretaria` e `/responsavel`, PEI dentro de `Document`). O modelo atual de roles/permissões/módulos e o PEI por matéria (`PeiSection`) descrito acima é o vigente. `Tests\Traits\CriaEscolaEUsuarios` já foi modernizado: seeda `RolesAndPermissionsSeeder` e o usuário do portal (`$this->secretaria`) usa a role built-in `admin` (a propriedade manteve o nome `secretaria` por compatibilidade); `$this->professor` = role `professor`; `$this->pai` existe só para checar bloqueio de acesso ao portal.
