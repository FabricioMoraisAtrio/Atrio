# Arquitetura e Rotas — Sistema Átrio

---

## Sumário

1. [Visão geral da arquitetura](#visão-geral-da-arquitetura)
2. [Fluxo de autenticação](#fluxo-de-autenticação)
3. [Mapa de rotas](#mapa-de-rotas)
4. [Models e relacionamentos](#models-e-relacionamentos)
5. [Services](#services)
6. [Fluxo de criação de documentos](#fluxo-de-criação-de-documentos)
7. [Fluxo de notificações](#fluxo-de-notificações)

---

## Visão geral da arquitetura

```
┌─────────────────────────────────────────────────────────┐
│                     BROWSER / CLIENT                     │
└────────────────────────┬────────────────────────────────┘
                         │ HTTP Request
┌────────────────────────▼────────────────────────────────┐
│                    LARAVEL ROUTING                        │
│  web.php → secretaria.php / professor.php / pai.php      │
│            routes/web.php → /superadmin (admin guard)    │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│                    MIDDLEWARE STACK                       │
│  web → auth → school.active → role:xxx                   │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│                    CONTROLLERS                            │
│  Secretaria/ | Professor/ | Pai/ | Admin/ | Auth/        │
└──────┬──────────────────┬──────────────────┬────────────┘
       │                  │                  │
┌──────▼──────┐  ┌────────▼───────┐  ┌──────▼──────┐
│   MODELS    │  │    SERVICES    │  │    VIEWS     │
│ +SchoolScope│  │DocumentContent │  │ Blade + CSS  │
└──────┬──────┘  └────────────────┘  └─────────────┘
       │
┌──────▼──────┐
│  DATABASE   │
│ SQLite/MySQL│
└─────────────┘
```

---

## Fluxo de autenticação

```
POST /login
    │
    ▼
LoginController
    │
    ├── Valida credenciais
    ├── Verifica is_active da escola
    ├── Grava school_id na sessão
    │
    └── Redireciona por role:
        ├── secretaria → /secretaria/dashboard
        ├── professor  → /professor/dashboard
        └── pai        → /responsavel/dashboard

POST /superadmin/login
    │
    ▼
Admin\LoginController (guard: admin)
    │
    └── Redireciona → /superadmin/dashboard
```

---

## Mapa de rotas

### Rotas públicas
| Método | URI | Nome | Controller |
|---|---|---|---|
| GET | `/login` | `login` | `LoginController@index` |
| POST | `/login` | `login.store` | `LoginController@store` |
| POST | `/logout` | `logout` | `LogoutController` |
| GET | `/superadmin/login` | `admin.login` | `Admin\LoginController@index` |
| POST | `/superadmin/login` | `admin.login.store` | `Admin\LoginController@store` |

### Rotas — Secretaria (`/secretaria/*`)
**Middleware:** `auth`, `school.active`, `role:secretaria`

| Método | URI | Nome | Controller |
|---|---|---|---|
| GET | `/secretaria/dashboard` | `secretaria.dashboard` | `DashboardController` |
| GET | `/secretaria/turmas` | `secretaria.turmas.index` | `SchoolClassController@index` |
| POST | `/secretaria/turmas` | `secretaria.turmas.store` | `SchoolClassController@store` |
| GET | `/secretaria/turmas/create` | `secretaria.turmas.create` | `SchoolClassController@create` |
| GET | `/secretaria/turmas/{turma}/show` | `secretaria.turmas.show` | `SchoolClassController@show` |
| GET | `/secretaria/turmas/{turma}/edit` | `secretaria.turmas.edit` | `SchoolClassController@edit` |
| PUT | `/secretaria/turmas/{turma}` | `secretaria.turmas.update` | `SchoolClassController@update` |
| DELETE | `/secretaria/turmas/{turma}` | `secretaria.turmas.destroy` | `SchoolClassController@destroy` |
| GET | `/secretaria/alunos` | `secretaria.alunos.index` | `StudentController@index` |
| POST | `/secretaria/alunos` | `secretaria.alunos.store` | `StudentController@store` |
| GET | `/secretaria/alunos/create` | `secretaria.alunos.create` | `StudentController@create` |
| GET | `/secretaria/alunos/{aluno}` | `secretaria.alunos.show` | `StudentController@show` |
| GET | `/secretaria/alunos/{aluno}/edit` | `secretaria.alunos.edit` | `StudentController@edit` |
| PUT | `/secretaria/alunos/{aluno}` | `secretaria.alunos.update` | `StudentController@update` |
| DELETE | `/secretaria/alunos/{aluno}` | `secretaria.alunos.destroy` | `StudentController@destroy` |
| POST | `/secretaria/alunos/{aluno}/turma` | `secretaria.alunos.attachClass` | `StudentController@attachClass` |
| GET | `/secretaria/alunos/{aluno}/documentos/create` | `secretaria.alunos.documentos.create` | `DocumentController@create` |
| POST | `/secretaria/alunos/{aluno}/documentos` | `secretaria.alunos.documentos.store` | `DocumentController@store` |
| GET | `/secretaria/documentos/{documento}` | `secretaria.documentos.show` | `DocumentController@show` |
| GET | `/secretaria/documentos/{documento}/edit` | `secretaria.documentos.edit` | `DocumentController@edit` |
| PUT | `/secretaria/documentos/{documento}` | `secretaria.documentos.update` | `DocumentController@update` |
| DELETE | `/secretaria/documentos/{documento}` | `secretaria.documentos.destroy` | `DocumentController@destroy` |
| GET | `/secretaria/documentos/{documento}/pdf` | `secretaria.documentos.pdf` | `DocumentPdfController` |
| GET | `/secretaria/documentos/{documento}/word` | `secretaria.documentos.word` | `DocumentWordController` |
| GET | `/secretaria/documentos` | `secretaria.documentos.index` | `AllDocumentsController` |
| GET | `/secretaria/usuarios` | `secretaria.usuarios.index` | `UserController@index` |
| POST | `/secretaria/usuarios` | `secretaria.usuarios.store` | `UserController@store` |
| GET | `/secretaria/usuarios/create` | `secretaria.usuarios.create` | `UserController@create` |
| GET | `/secretaria/usuarios/{usuario}/edit` | `secretaria.usuarios.edit` | `UserController@edit` |
| PUT | `/secretaria/usuarios/{usuario}` | `secretaria.usuarios.update` | `UserController@update` |
| DELETE | `/secretaria/usuarios/{usuario}` | `secretaria.usuarios.destroy` | `UserController@destroy` |
| POST | `/secretaria/alunos/{aluno}/observacoes` | `secretaria.alunos.observacoes.store` | `ObservationController@store` |
| DELETE | `/secretaria/observacoes/{observation}` | `secretaria.observacoes.destroy` | `ObservationController@destroy` |

### Rotas — Professor (`/professor/*`)
**Middleware:** `auth`, `school.active`, `role:professor`

| Método | URI | Nome | Controller |
|---|---|---|---|
| GET | `/professor/dashboard` | `professor.dashboard` | `DashboardController` |
| GET | `/professor/documentos` | `professor.documentos.index` | `DocumentController@index` |
| GET | `/professor/alunos/{aluno}/documentos/create` | `professor.alunos.documentos.create` | `DocumentController@create` |
| POST | `/professor/alunos/{aluno}/documentos` | `professor.alunos.documentos.store` | `DocumentController@store` |
| GET | `/professor/documentos/{documento}` | `professor.documentos.show` | `DocumentController@show` |
| GET | `/professor/documentos/{documento}/edit` | `professor.documentos.edit` | `DocumentController@edit` |
| PUT | `/professor/documentos/{documento}` | `professor.documentos.update` | `DocumentController@update` |
| GET | `/professor/documentos/{documento}/pdf` | `professor.documentos.pdf` | `DocumentPdfController` |
| GET | `/professor/documentos/{documento}/word` | `professor.documentos.word` | `DocumentWordController` |
| GET | `/professor/alunos/{aluno}` | `professor.alunos.show` | `StudentController@show` |
| POST | `/professor/alunos/{aluno}/observacoes` | `professor.alunos.observacoes.store` | `ObservationController@store` |
| DELETE | `/professor/observacoes/{observation}` | `professor.observacoes.destroy` | `ObservationController@destroy` |

### Rotas — Pai (`/responsavel/*`)
**Middleware:** `auth`, `school.active`, `role:pai`

| Método | URI | Nome | Controller |
|---|---|---|---|
| GET | `/responsavel/dashboard` | `pai.dashboard` | `DashboardController` |
| GET | `/responsavel/documentos/{documento}/pdf` | `pai.documentos.pdf` | `DocumentPdfController` |

### Rotas — Perfil (todos os usuários autenticados)
| Método | URI | Nome | Controller |
|---|---|---|---|
| GET | `/perfil` | `profile.edit` | `ProfileController@edit` |
| PUT | `/perfil` | `profile.update` | `ProfileController@update` |

### Rotas — Admin (`/superadmin/*`)
**Middleware:** `admin.auth`

| Método | URI | Nome | Controller |
|---|---|---|---|
| GET | `/superadmin/dashboard` | `admin.dashboard` | `Admin\DashboardController` |
| GET | `/superadmin/schools` | `admin.schools.index` | `Admin\SchoolController@index` |
| POST | `/superadmin/schools` | `admin.schools.store` | `Admin\SchoolController@store` |
| GET | `/superadmin/schools/create` | `admin.schools.create` | `Admin\SchoolController@create` |
| GET | `/superadmin/schools/{school}` | `admin.schools.show` | `Admin\SchoolController@show` |
| GET | `/superadmin/schools/{school}/edit` | `admin.schools.edit` | `Admin\SchoolController@edit` |
| PUT | `/superadmin/schools/{school}` | `admin.schools.update` | `Admin\SchoolController@update` |
| DELETE | `/superadmin/schools/{school}` | `admin.schools.destroy` | `Admin\SchoolController@destroy` |
| POST | `/superadmin/schools/{school}/reset-password/{user}` | `admin.schools.resetPassword` | `Admin\SchoolController@resetPassword` |

---

## Models e relacionamentos

### School
```php
hasMany(User::class)
hasMany(SchoolClass::class)
hasMany(Student::class)
hasMany(Document::class)
```

### User
```php
belongsTo(School::class)
belongsToMany(SchoolClass::class) // pivot: subject
belongsToMany(Student::class, 'student_user') // para pais — relação "children"
// Roles via Spatie HasRoles
```

### SchoolClass
```php
belongsTo(School::class)
belongsToMany(User::class)     // professores
belongsToMany(Student::class)  // alunos
```

### Student
```php
belongsTo(School::class)
belongsToMany(SchoolClass::class)
belongsToMany(User::class, 'student_user') // pais
hasMany(Document::class)
hasMany(Observation::class)
```

### Document
```php
belongsTo(School::class)
belongsTo(Student::class)
belongsTo(User::class, 'author_id')
// content: cast para array (JSON)
// GlobalScope: SchoolScope
```

### Observation
```php
belongsTo(School::class)
belongsTo(Student::class)
belongsTo(User::class)
// GlobalScope: SchoolScope
```

---

## Services

### `DocumentContentService`
`app/Services/DocumentContentService.php`

Centraliza a montagem do array de conteúdo dos documentos a partir do request HTTP.

```php
DocumentContentService::buildContent(string $type, Request $request): array
```

Elimina duplicação entre os controllers de secretaria e professor.

---

## Fluxo de criação de documentos

```
Usuário acessa /create?type=pei
        │
        ▼
DocumentController@create
        │
        ├── Verifica acesso (professor: verifica turma)
        ├── Verifica se Estudo de Caso existe (para PEI/PAEE)
        ├── Verifica se documento já existe no ano
        │
        └── Renderiza view com formulário
                │
                ▼
        Usuário submete POST /store
                │
                ▼
        DocumentController@store
                │
                ├── Valida type
                ├── Re-verifica regras de negócio
                ├── DocumentContentService::buildContent()
                ├── Document::create()
                ├── Atualiza has_case_study no aluno (se estudo_caso)
                │
                └── Cache::forget('pendentes_count_...')
                        │
                        └── Redireciona com sucesso
```

---

## Fluxo de notificações

```
Diário às 07:00 (Laravel Scheduler)
        │
        ▼
php artisan atrio:notificacoes-diarias
        │
        ├── notificarDocumentosPendentes()
        │       ├── Busca alunos atípicos por escola
        │       ├── Filtra os com documentos faltando
        │       ├── Busca usuários com notify_document_pending = true
        │       └── Enqueue: DocumentosPendentesNotification (delay escalonado)
        │
        └── notificarPlanoVencendo()
                ├── Verifica dias restantes do plano
                ├── Notifica apenas nos dias: 30, 15, 7, 3, 1
                └── Enqueue: PlanoVencendoNotification

Imediato — Observação crítica
        │
        ▼
ObservationController@store
        └── Se urgency === 'critico':
                └── Enqueue: ObservacaoCriticaNotification → secretaria

Imediato — Novo documento
        │
        ▼
DocumentController@store
        └── Para cada pai do aluno:
                └── Enqueue: NovoDocumentoPaiNotification

Queue Worker processa jobs
        └── php artisan queue:work --sleep=3 --tries=3
```
