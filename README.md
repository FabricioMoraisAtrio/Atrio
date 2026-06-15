# Átrio

Sistema Átrio — SaaS multi-escola (multitenancy) para gestão de inclusão escolar: PEI, PAEE, Estudo de Caso, Laudos, Observações, Jornada Alimentar/Seletividade e Adaptações para Prova.

**Stack:** Laravel 12 (PHP 8.2) · Blade · Tailwind CSS 4 · Vite · SQLite (dev)

## Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- SQLite (ou outro driver suportado pelo Laravel)

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
```

Ou, de uma vez:

```bash
composer run setup
```

## Ambiente de desenvolvimento

```bash
# Server + queue + logs + vite, tudo junto
composer dev

# Apenas assets (modo watch)
npm run dev
```

## Testes

```bash
php artisan test
php artisan test --filter NomeDoTeste

# Limpa config antes de testar
composer test
```

O ambiente de teste (`phpunit.xml`) usa SQLite em memória, fila `sync` e e-mail `array`.

## Banco de dados

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed
```

## Fila (driver database)

```bash
php artisan queue:work --sleep=3 --tries=3
```

## Notificações diárias

Rodam via scheduler às 07:00, ou manualmente:

```bash
php artisan atrio:notificacoes-diarias
```

Também disponível via `/cron/notificacoes` (com token).

## Code style

```bash
vendor/bin/pint
```

Preset `laravel`, com a regra `no_unused_imports` desabilitada.

## Arquitetura

### Multitenancy

Toda tabela de dados tem `school_id`. O global scope `App\Scopes\SchoolScope` filtra automaticamente por `session('school_id')` — aplicado em `School`, `SchoolClass`, `Student`, `Document`, `PeiSection`, `Subject`, `SubjectInventoryItem`, `Laudo`, `Observation`, `DocumentAccessLog`. O model `User` **não** usa o scope (conflita com queries internas do Spatie Permission). `session('school_id')` é gravado no login.

### Roles, permissões e módulos

- Permissões/roles via `spatie/laravel-permission`, seedadas em `database/seeders/RolesAndPermissionsSeeder.php`.
- Roles built-in: `admin`, `coordenador`, `orientador` (acesso ao "portal") e `professor`.
- Cada escola pode criar **roles customizados** via `App\Models\SchoolRole`, mapeados para roles Spatie com prefixo `s{school_id}_` e permissões próprias.
- Rotas de portal usam middleware `can:<permissao>` (ex.: `can:alunos.ver`, `can:documentos.ver_todos`).
- `EnsureSchoolMember` (`school.member`) libera acesso ao portal para roles built-in ou para qualquer role `s{school_id}_*`.
- `EnsureSchoolHasModule` (`school.module:<chave>`) bloqueia rotas se o módulo não estiver habilitado em `School.modules`. Módulos disponíveis: `painel`, `alunos`, `documentos`, `turmas`, `adaptacoes`, `materias`, `usuarios`, `configuracoes`, `seletividade`.
- `EnsureSchoolIsActive` (`school.active`) faz logout se a escola estiver inativa ou com plano vencido.
- Guard separado `admin` (`AdminUser`) para o painel SaaS em `/superadmin`.

### Rotas

- `routes/web.php`: rotas públicas (login, reset de senha, landing, termos) + grupo `auth + school.active` que carrega:
  - `routes/secretaria.php` (prefixo `/portal`, nome `secretaria.*`, middleware `school.member`);
  - `routes/professor.php` (prefixo `/professor`, nome `professor.*`, middleware `role:professor`).
- `/superadmin/*` é guard `admin`, fora do middleware de auth padrão.

### Terminologia customizável

`term('chave')` (em `app/Helpers/helpers.php`) retorna o termo customizado da escola (`SchoolSetting`, prefixo `term_*`) ou um default do sistema, permitindo que cada escola renomeie conceitos.

### Módulo de Documentos / PEI

- `Document` (`school_id`, `student_id`, `author_id`, `type`, `year`, `status`, `content` JSON) cobre os tipos `estudo_caso` e `paee`. Estudo de Caso é pré-requisito para PAEE; um documento por tipo/aluno/ano.
- `App\Services\DocumentContentService::buildContent(type, request)` centraliza a montagem do `content`.
- O **PEI** é por matéria, no model `PeiSection` (`student_id`, `subject_id`, `author_id`, `year`, `content` JSON). Cada professor edita a seção da sua matéria; a secretaria consolida via `Secretaria\PeiConsolidadoController`.
- `Subject` (matérias da escola) tem `inventoryItems` (`SubjectInventoryItem`) e `peiSections`.
- Exportação: PDF via `barryvdh/laravel-dompdf`, Word via `phpoffice/phpword`.

### Outros módulos

- `Laudo`: laudos médicos/psicológicos anexados ao aluno.
- `Observation`: mural de observações por aluno; urgência `critico` dispara notificação.
- `StudentFoodItem` (Jornada Alimentar / Seletividade): aceitação alimentar por categoria e status.
- `Student`: flags `cid_*`; `PUBLICO_ALVO_FIELDS` define quais condições enquadram o aluno como Público Alvo da Educação Especial.

### Notificações

Todas implementam `ShouldQueue`: `DocumentosPendentesNotification`, `ObservacaoCriticaNotification`, `NovoDocumentoPaiNotification`, `PlanoVencendoNotification`. Preferências por usuário: `notify_document_pending`, `notify_plan_expiring`.

## Documentação adicional

`ARQUITETURA_E_ROTAS.md`, `DOCUMENTACAO_TECNICA.md`, `GUIA_IMPLANTACAO.md` e `MANUAL_USUARIO.md` trazem visão geral e detalhes de deploy, mas podem descrever versões anteriores do sistema — o modelo de roles/permissões/módulos e o PEI por matéria descritos acima é o vigente.
