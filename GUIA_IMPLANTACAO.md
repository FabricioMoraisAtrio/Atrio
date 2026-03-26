# Guia de Implantação — Sistema Átrio

---

## Sumário

1. [Requisitos](#requisitos)
2. [Instalação local](#instalação-local)
3. [Configuração do ambiente](#configuração-do-ambiente)
4. [Banco de dados](#banco-de-dados)
5. [Dados iniciais](#dados-iniciais)
6. [Filas e agendamentos](#filas-e-agendamentos)
7. [Deploy em produção](#deploy-em-produção)
8. [Variáveis de ambiente](#variáveis-de-ambiente)

---

## Requisitos

| Requisito | Versão mínima |
|---|---|
| PHP | 8.2 |
| Laravel | 12.x |
| Node.js | 18.x |
| Composer | 2.x |
| Banco de dados | SQLite (dev) / MySQL 8+ / PostgreSQL 14+ (prod) |
| Extensões PHP | zip, pdo, mbstring, openssl, tokenizer, xml, ctype, json |

---

## Instalação local

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/atrio-v2.git
cd atrio-v2

# 2. Instale as dependências PHP
composer install

# 3. Instale as dependências JS
npm install

# 4. Copie o arquivo de ambiente
cp .env.example .env

# 5. Gere a chave da aplicação
php artisan key:generate

# 6. Configure o banco de dados no .env (veja seção abaixo)

# 7. Execute as migrations
php artisan migrate

# 8. Popule os dados iniciais
php artisan db:seed

# 9. Crie o link simbólico para storage
php artisan storage:link

# 10. Compile os assets
npm run dev

# 11. Inicie o servidor
php artisan serve
```

Acesse: `http://localhost:8000`

---

## Configuração do ambiente

Edite o arquivo `.env` com as configurações do seu ambiente:

### Banco de dados (desenvolvimento — SQLite)
```env
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/absoluto/para/database/database.sqlite
```

### Banco de dados (produção — MySQL)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atrio
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### E-mail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.seuservidor.com
MAIL_PORT=587
MAIL_USERNAME=seu@email.com
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@suaescola.edu.br
MAIL_FROM_NAME="Sistema Átrio"
```

> Para desenvolvimento, use o [Mailtrap](https://mailtrap.io) como servidor SMTP de teste.

### Filas
```env
QUEUE_CONNECTION=database
```

---

## Banco de dados

### Criar o arquivo SQLite (apenas para desenvolvimento)
```bash
touch database/database.sqlite
```

### Executar migrations
```bash
php artisan migrate
```

### Reverter migrations
```bash
php artisan migrate:rollback
```

### Recriar do zero
```bash
php artisan migrate:fresh --seed
```

---

## Dados iniciais

O seeder cria os seguintes dados de demonstração:

```bash
php artisan db:seed
```

| Perfil | E-mail | Senha |
|---|---|---|
| Secretaria | secretaria@atrio.com.br | password |
| Professor | professor@atrio.com.br | password |
| Responsável | pai@atrio.com.br | password |
| Super Admin | admin@atrio.com.br | admin123 |

O Super Admin acessa o painel administrativo em `/superadmin`.

---

## Filas e agendamentos

### Processar fila manualmente (desenvolvimento)
```bash
php artisan queue:work --sleep=3 --tries=3
```

### Processar fila em produção (supervisor recomendado)
Configure o Supervisor para manter o worker sempre ativo:

```ini
[program:atrio-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/atrio/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/atrio/storage/logs/worker.log
stopwaitsecs=3600
```

### Agendamentos (Scheduler)

O sistema possui um comando diário de notificações. Configure um cron no servidor:

```bash
* * * * * cd /var/www/atrio && php artisan schedule:run >> /dev/null 2>&1
```

O scheduler executa `atrio:notificacoes-diarias` todos os dias às **07:00**.

---

## Deploy em produção

```bash
# 1. Clone ou atualize o repositório
git pull origin main

# 2. Instale dependências sem dev
composer install --no-dev --optimize-autoloader

# 3. Execute migrations
php artisan migrate --force

# 4. Compile assets para produção
npm run build

# 5. Limpe e regenere caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Reinicie o worker
php artisan queue:restart
```

### Permissões de pasta (Linux)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Variáveis de ambiente

| Variável | Descrição | Padrão |
|---|---|---|
| `APP_NAME` | Nome da aplicação | `Átrio` |
| `APP_ENV` | Ambiente (`local`, `production`) | `local` |
| `APP_DEBUG` | Modo debug | `true` |
| `APP_URL` | URL base da aplicação | `http://localhost` |
| `DB_CONNECTION` | Driver do banco | `sqlite` |
| `QUEUE_CONNECTION` | Driver da fila | `sync` |
| `MAIL_MAILER` | Driver de e-mail | `log` |
| `MAIL_FROM_ADDRESS` | Remetente dos e-mails | — |
| `CACHE_DRIVER` | Driver de cache | `file` |
| `SESSION_DRIVER` | Driver de sessão | `file` |

> Em produção, defina sempre `APP_ENV=production` e `APP_DEBUG=false`.
