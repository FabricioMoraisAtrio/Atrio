# Documentação Técnica — Sistema Átrio

---

## Sumário

1. [Stack e dependências](#stack-e-dependências)
2. [Estrutura do projeto](#estrutura-do-projeto)
3. [Arquitetura e decisões](#arquitetura-e-decisões)
4. [Banco de dados](#banco-de-dados)
5. [Autenticação e autorização](#autenticação-e-autorização)
6. [Multitenancy](#multitenancy)
7. [Módulo de documentos](#módulo-de-documentos)
8. [Notificações](#notificações)
9. [Filas](#filas)
10. [Cache](#cache)
11. [Testes](#testes)
12. [Design system](#design-system)

---

## Stack e dependências

| Camada | Tecnologia |
|---|---|
| Framework | Laravel 12.x |
| Linguagem | PHP 8.2 |
| Banco (dev) | SQLite |
| Banco (prod) | MySQL 8+ / PostgreSQL 14+ |
| Frontend | Blade + Tailwind CSS + Vite |
| Autenticação | Laravel Auth nativo |
| Autorização | `spatie/laravel-permission` |
| PDF | `barryvdh/laravel-dompdf` |
| Word | `phpoffice/phpword` |
| Testes | PHPUnit 11 |

---

## Estrutura do projeto

```
app/
├── Console/Commands/
│   └── EnviarNotificacoesDiarias.php   # Comando de notificações diárias
├── Http/
│   ├── Controllers/
│   │   ├── Admin/                      # Painel SaaS superadmin
│   │   ├── Auth/                       # Login e logout
│   │   ├── Pai/                        # Dashboard e PDF para pais
│   │   ├── Professor/                  # Dashboard, documentos, observações
│   │   ├── Secretaria/                 # CRUD completo
│   │   └── ProfileController.php       # Perfil do usuário
│   └── Middleware/
│       ├── EnsureSchoolIsActive.php    # Verifica plano ativo
│       └── EnsureAdminIsAuthenticated.php
├── Models/
│   ├── School.php
│   ├── User.php
│   ├── SchoolClass.php
│   ├── Student.php
│   ├── Document.php
│   ├── Observation.php
│   ├── DocumentAccessLog.php
│   └── AdminUser.php
├── Notifications/
│   ├── DocumentosPendentesNotification.php
│   ├── ObservacaoCriticaNotification.php
│   ├── NovoDocumentoPaiNotification.php
│   └── PlanoVencendoNotification.php
├── Scopes/
│   └── SchoolScope.php                 # Global scope de multitenancy
└── Services/
    └── DocumentContentService.php      # Centraliza lógica de conteúdo

routes/
├── web.php          # Rotas base + auth + superadmin
├── secretaria.php   # Rotas do perfil secretaria
├── professor.php    # Rotas do perfil professor
└── pai.php          # Rotas do perfil pai

tests/
├── Feature/
│   ├── AuthTest.php
│   ├── AlunoTest.php
│   ├── DocumentoTest.php
│   └── ObservacaoTest.php
├── Unit/
│   └── NotificacaoTest.php
└── Traits/
    └── CriaEscolaEUsuarios.php
```

---

## Arquitetura e decisões

### Multitenancy por `school_id`
Todas as tabelas de dados possuem `school_id`. O `SchoolScope` aplica automaticamente o filtro em todas as queries dos models com `addGlobalScope`.

**Exceção**: o model `User` não usa o `SchoolScope` para evitar conflito com o Spatie Permission que faz queries internas sem contexto de escola.

### Rotas separadas por perfil
As rotas de cada role estão em arquivos separados (`secretaria.php`, `professor.php`, `pai.php`) e são carregadas com prefixo e middleware de role no `web.php`:

```php
Route::prefix('secretaria')->middleware(['auth', 'school.active', 'role:secretaria'])
    ->name('secretaria.')
    ->group(base_path('routes/secretaria.php'));
```

### Guards
- `web` — usuários das escolas (secretaria, professor, pai)
- `admin` — superadmin (`/superadmin`)

---

## Banco de dados

### Diagrama simplificado

```
schools
  └── users (school_id)
  └── school_classes (school_id)
        └── school_class_user (pivot professor ↔ turma, com subject)
        └── school_class_student (pivot aluno ↔ turma)
  └── students (school_id)
        └── student_user (pivot pai ↔ aluno)
        └── documents (school_id, student_id, author_id)
        └── observations (school_id, student_id, user_id)
  └── document_access_logs (document_id, user_id)

admin_users (tabela separada, guard admin)
```

### Tabelas principais

| Tabela | Descrição |
|---|---|
| `schools` | Escolas cadastradas no SaaS |
| `users` | Usuários das escolas (secretaria, professor, pai) |
| `school_classes` | Turmas com nome, turno e ano |
| `students` | Alunos com flags de CID |
| `documents` | PEI, PAEE e Estudo de Caso em JSON |
| `observations` | Mural de observações por aluno |
| `document_access_logs` | Log LGPD de acesso a documentos |
| `admin_users` | Administradores do painel SaaS |
| `jobs` | Fila de jobs para notificações |

### Campos de atipicidade (students)

```
is_atypical              boolean
condition                string (texto livre para "Outros")
has_case_study           boolean
cid_autismo              boolean
cid_tdah                 boolean
cid_down                 boolean
cid_deficiencia_intelectual boolean
cid_deficiencia_visual   boolean
cid_deficiencia_auditiva boolean
cid_outros               boolean
```

---

## Autenticação e autorização

### Login
`LoginController` valida as credenciais, grava `school_id` na sessão e redireciona por role:

```php
session(['school_id' => $user->school_id]);

return match($user->getRoleNames()->first()) {
    'secretaria' => redirect()->route('secretaria.dashboard'),
    'professor'  => redirect()->route('professor.dashboard'),
    'pai'        => redirect()->route('pai.dashboard'),
};
```

### Roles (Spatie)
- `secretaria` — acesso total à escola
- `professor` — acesso às turmas vinculadas
- `pai` — acesso somente leitura aos filhos

### Middleware customizado
`EnsureSchoolIsActive` verifica a cada request se a escola está ativa e se o plano não venceu. Se inativo, faz logout e redireciona para o login.

---

## Multitenancy

O `SchoolScope` em `app/Scopes/SchoolScope.php` filtra automaticamente todos os models por `school_id` baseado na sessão:

```php
public function apply(Builder $builder, Model $model): void
{
    $builder->where($model->getTable() . '.school_id', session('school_id'));
}
```

**Models que usam SchoolScope:**
- `School`, `SchoolClass`, `Student`, `Document`, `Observation`, `DocumentAccessLog`

**Models que NÃO usam:**
- `User` (conflito com Spatie)

---

## Módulo de documentos

### Tipos
- `estudo_caso` — campos: historico, barreiras, potencialidades, observacoes_livres
- `pei` — campos: objetivos, adaptacoes, avaliacao, progresso, observacoes_livres
- `paee` — campos: cronograma, recursos, acessibilidade, parcerias, observacoes_livres

### Conteúdo
O campo `content` é armazenado como JSON no banco. O `DocumentContentService::buildContent()` centraliza a montagem do array de conteúdo a partir do request.

### Regras de negócio
1. **Estudo de Caso obrigatório** antes de criar PEI ou PAEE.
2. **Um documento por tipo por aluno por ano** — verificado no `store` e com unique constraint no banco.
3. **Professor edita documentos de alunos das suas turmas** — verificado via `verificarAcesso()`.
4. **Secretaria edita qualquer documento** da escola.

### Exportação PDF
Usa `barryvdh/laravel-dompdf`. O controller gera o HTML via view `resources/views/pdf/documento.blade.php` e retorna como stream.

### Exportação Word
Usa `phpoffice/phpword`. O `Professor\DocumentWordController::gerarWord()` centraliza a geração e é reutilizado pela secretaria.

---

## Notificações

### Tipos

| Notification | Trigger | Destinatário |
|---|---|---|
| `DocumentosPendentesNotification` | Diário às 07:00 | Secretaria + Professor |
| `ObservacaoCriticaNotification` | Observação com urgência crítica | Secretaria |
| `NovoDocumentoPaiNotification` | Novo documento criado | Pai do aluno |
| `PlanoVencendoNotification` | 30, 15, 7, 3 e 1 dia antes | Secretaria |

### Preferências
O usuário controla o recebimento via `notify_document_pending` e `notify_plan_expiring` (booleans na tabela `users`).

### Comando manual
```bash
php artisan atrio:notificacoes-diarias
```

---

## Filas

Todas as notificações implementam `ShouldQueue` com `Queueable`. A fila usa o driver `database` em produção.

### Processar
```bash
php artisan queue:work --sleep=3 --tries=3
```

### Limpar fila
```bash
php artisan queue:clear
php artisan queue:flush  # remove jobs com falha
```

---

## Cache

O badge de pendentes na sidebar usa cache com TTL de 5 minutos:

```php
Cache::remember('pendentes_count_' . $schoolId, now()->addMinutes(5), fn() => ...);
```

O cache é invalidado automaticamente quando um documento é criado ou deletado via o evento `saved/deleted` no model `Document`.

### Limpar cache
```bash
php artisan cache:clear
```

---

## Testes

### Executar todos
```bash
php artisan test
```

### Executar grupo específico
```bash
php artisan test --filter AuthTest
php artisan test --filter DocumentoTest
```

### Cobertura
```bash
php artisan test --coverage
```

### Trait auxiliar
`Tests\Traits\CriaEscolaEUsuarios` — cria escola, roles e os 3 usuários de teste. Usado em todos os feature tests.

### Ambiente de testes
Configurado em `phpunit.xml` para usar SQLite em memória, fila síncrona e e-mail em array (sem envio real).

---

## Design system

### Paleta de cores

| Token | Valor | Uso |
|---|---|---|
| Primary | `#004B8D` | Ações principais, links, sidebar ativo |
| Secondary | `#009C8C` | Turmas, badges de alunos |
| Tertiary | `#7C3700` | Documentos, usuários |
| Neutral | `#CBCBCB` | Bordas, separadores |

### Dark mode
Implementado via CSS variables em `layouts/app.blade.php`. A preferência é persistida em `localStorage`. O tema é aplicado via `data-theme="dark"` no elemento `<html>`.

### Componentes Blade
- `resources/views/components/observation-feed.blade.php` — mural de observações reutilizável
- `resources/views/layouts/partials/icon.blade.php` — ícones SVG da sidebar

### Partials de documentos
Os campos de formulário dos documentos estão em partials reutilizados pelo create e edit:
- `secretaria/documentos/partials/estudo_caso.blade.php`
- `secretaria/documentos/partials/pei.blade.php`
- `secretaria/documentos/partials/paee.blade.php`
- `secretaria/documentos/partials/observacoes_livres.blade.php`
