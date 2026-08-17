<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secretaria\AllDocumentsController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Serve logos de escola
Route::get('/logo/{filename}', function (string $filename) {
    $storagePath = storage_path('app/public/logos/' . $filename);
    $publicPath  = base_path('public/logos/' . $filename);
    if (file_exists($storagePath)) return response()->file($storagePath);
    if (file_exists($publicPath))  return response()->file($publicPath);
    abort(404);
})->where('filename', '[^/]+')->name('school.logo');

// Esqueceu a senha
Route::get('/esqueceu-senha', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/esqueceu-senha', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    try {
        $status = Password::sendResetLink($request->only('email'));
    } catch (\Throwable $e) {
        report($e); // não derruba a página (ex.: SMTP indisponível)
        return back()->withErrors([
            'email' => 'Não foi possível enviar o e-mail agora. Tente novamente em instantes ou contate o suporte.',
        ]);
    }

    if ($status === Password::RESET_THROTTLED) {
        return back()->withErrors(['email' => 'Aguarde um momento antes de tentar novamente.']);
    }

    // Mensagem genérica (não revela se o e-mail existe) — evita enumeração de usuários.
    return back()->with('success', 'Se este e-mail estiver cadastrado, enviamos o link de redefinição.');
})->middleware('throttle:5,1')->name('password.email');

// Redefinir senha
Route::get('/redefinir-senha/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/redefinir-senha', function (Request $request) {
    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Senha redefinida com sucesso.')
        : back()->withErrors(['email' => 'Token inválido ou expirado.']);
})->middleware('throttle:6,1')->name('password.update');


Route::get('/cron/notificacoes', function () {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);
    \Artisan::call('atrio:notificacoes-diarias');
    \Artisan::call('atrio:faturas-vencendo');
    // processa a fila no mesmo passo (hospedagem sem worker dedicado)
    \Artisan::call('queue:work', ['--stop-when-empty' => true, '--max-time' => 50, '--tries' => 3]);
    return response()->json(['ok' => true]);
});

// Cabeçalhos para impedir cache das respostas /cron/* (HostGator/LiteSpeed
// costuma cachear GET, o que confunde o diagnóstico).
$noStore = fn ($response) => $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

// Processa a fila (notificações/jobs) — agende a cada poucos minutos.
// Necessário porque a hospedagem compartilhada não mantém um queue:work ativo.
Route::get('/cron/fila', function () use ($noStore) {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    \Artisan::call('queue:work', [
        '--stop-when-empty' => true,
        '--max-time'        => 50,
        '--tries'           => 3,
        '--no-interaction'  => true,
    ]);

    return $noStore(response()->json(['ok' => true, 'saida' => trim(\Artisan::output())]));
});

// Backup do banco (.sql.gz em storage/app/backups). Agende 1x/dia. Mesmo token.
Route::get('/cron/backup', function () use ($noStore) {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    \Artisan::call('atrio:backup', ['--no-interaction' => true]);

    return $noStore(response('<pre>' . e(trim(\Artisan::output())) . '</pre>'));
});

// Limpa caches (config/route/view) — recuperação pós-deploy. NÃO depende do banco,
// para funcionar mesmo com a conexão fora (ex.: credencial de DB errada). Mesmo token.
Route::get('/cron/clear', function () use ($noStore) {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    $out = [];
    foreach (['config:clear', 'route:clear', 'view:clear'] as $cmd) {
        \Artisan::call($cmd);
        $out[] = trim(\Artisan::output());
    }
    // cache:clear usa o banco (store database) — tenta, mas não derruba se falhar
    try {
        \Artisan::call('cache:clear');
        $out[] = trim(\Artisan::output());
    } catch (\Throwable $e) {
        $out[] = 'cache:clear ignorado: ' . $e->getMessage();
    }

    return $noStore(response('<pre>' . e(implode("\n", array_filter($out))) . '</pre>'));
});

// Mostra o fim do log de erros (diagnóstico). Mesmo token.
Route::get('/cron/last-error', function () use ($noStore) {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    $path = storage_path('logs/laravel.log');
    if (! is_file($path)) {
        return $noStore(response('<pre>(sem arquivo de log)</pre>'));
    }
    $size = filesize($path);
    $read = (int) min($size, 25000);
    $fh = fopen($path, 'rb');
    if ($read > 0) {
        fseek($fh, -$read, SEEK_END);
    }
    $data = fread($fh, max($read, 1));
    fclose($fh);

    // Defesa extra: mascara valores sensíveis caso o token vaze.
    $data = preg_replace('/\b(password|passwd|secret|token|api[_-]?key|bearer|authorization)\b(["\']?\s*[:=]\s*)([^\s,"\']+)/i', '$1$2[oculto]', (string) $data);

    return $noStore(response('<pre>' . e($data) . '</pre>'));
});

// Teste de SMTP — restrito ao superadmin logado (sem token na URL).
// Uso: faça login no /superadmin e acesse /cron/mail-test?to=seu@email.com
Route::get('/cron/mail-test', function () use ($noStore) {
    $cfg = config('mail.mailers.' . config('mail.default'), []);
    $pw  = (string) ($cfg['password'] ?? '');

    $info = [
        'mailer'          => config('mail.default'),
        'host'            => $cfg['host'] ?? null,
        'port'            => $cfg['port'] ?? null,
        'scheme'          => $cfg['scheme'] ?? '(vazio)',
        'username'        => $cfg['username'] ?? null,
        'password_len'    => strlen($pw),
        'from_address'    => config('mail.from.address'),
        'from_name'       => config('mail.from.name'),
        'config_em_cache' => is_file(base_path('bootstrap/cache/config.php')) ? 'SIM (rode /cron/clear apos editar .env)' : 'nao',
    ];

    $to = (string) request('to');
    if ($to === '') {
        $info['dica'] = 'adicione &to=seu@email.com na URL para enviar um teste';
        return $noStore(response('<pre>' . e(json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>'));
    }

    try {
        \Illuminate\Support\Facades\Mail::raw('Teste de e-mail do Átrio — se você recebeu isto, o SMTP está OK.', function ($m) use ($to) {
            $m->to($to)->subject('Átrio — teste de SMTP');
        });
        $info['envio'] = 'OK ✔ — verifique a caixa de entrada e o spam de ' . $to;
    } catch (\Throwable $e) {
        $info['envio'] = 'FALHOU: ' . $e->getMessage();
    }

    return $noStore(response('<pre>' . e(json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>'));
})->middleware('admin.auth');

// Executa migrations pendentes via URL (hospedagem compartilhada sem SSH).
// Exige token NÃO-VAZIO definido em CRON_TOKEN, para não ficar exposta.
Route::get('/cron/migrate', function () use ($noStore) {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    try {
        \Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);

        return $noStore(response('<pre>' . e(\Artisan::output()) . '</pre>'));
    } catch (\Throwable $e) {
        return $noStore(response(
            '<pre>ERRO ao migrar:' . "\n" . e($e->getMessage()) . "\n\n--- saída parcial ---\n" . e(\Artisan::output()) . '</pre>',
            500
        ));
    }
});

// Mostra o status das migrations (quais já rodaram / pendentes). Mesmo token.
Route::get('/cron/migrate-status', function () use ($noStore) {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    \Artisan::call('migrate:status');

    return $noStore(response('<pre>' . e(\Artisan::output()) . '</pre>'));
});

// Diagnóstico autoritativo: lê direto o banco (ignora formatação/cache do status).
Route::get('/cron/db-info', function () use ($noStore) {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    $temLogs = \Illuminate\Support\Facades\Schema::hasTable('document_access_logs');

    $info = [
        'connection'                  => config('database.default'),
        'database'                    => \Illuminate\Support\Facades\DB::connection()->getDatabaseName(),
        'TABELA student_academic_goals' => \Illuminate\Support\Facades\Schema::hasTable('student_academic_goals') ? 'EXISTE' : 'NAO EXISTE',
        'COLUNA document_access_logs.school_id' => ($temLogs && \Illuminate\Support\Facades\Schema::hasColumn('document_access_logs', 'school_id')) ? 'EXISTE' : 'NAO EXISTE',
        'TABELA document_access_logs_old (residuo)' => \Illuminate\Support\Facades\Schema::hasTable('document_access_logs_old') ? 'EXISTE' : 'nao existe',
        'migrations registradas (2026_06)' => \Illuminate\Support\Facades\DB::table('migrations')
            ->where('migration', 'like', '%2026_06_15%')
            ->orWhere('migration', 'like', '%2026_06_22%')
            ->pluck('migration')->all(),
        'total de logs de acesso'     => $temLogs ? \Illuminate\Support\Facades\DB::table('document_access_logs')->count() : 'n/a',
        'total de materias'           => \Illuminate\Support\Facades\Schema::hasTable('subjects') ? \Illuminate\Support\Facades\DB::table('subjects')->count() : 'n/a',
    ];

    return $noStore(response('<pre>' . e(json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . '</pre>'));
});

// Site institucional (marketing) — layout + partials compartilhados em resources/views/marketing
Route::get('/',            fn() => view('marketing.home'))->name('home');
Route::get('/plataforma',  fn() => view('marketing.plataforma'))->name('plataforma');
Route::get('/plataforma/{modulo}', function (string $modulo) {
    $m = \App\Support\Modulos::find($modulo);
    abort_if(! $m, 404);
    return view('marketing.modulo', ['m' => $m]);
})->name('modulo');
Route::get('/planos',      fn() => view('marketing.planos'))->name('planos');
Route::get('/legislacao',  fn() => view('marketing.legislacao'))->name('legislacao');
Route::get('/duvidas',     fn() => view('marketing.duvidas'))->name('duvidas');
Route::get('/contato',     fn() => view('marketing.contato'))->name('contato');

Route::get('/entrar', [LoginController::class, 'create'])->name('login');
Route::post('/entrar', [LoginController::class, 'store'])->middleware('throttle:10,1')->name('login.store');

Route::middleware(['auth', 'school.active'])->group(function () {
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');

    Route::get('/perfil', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/perfil/tema', [\App\Http\Controllers\ProfileController::class, 'updateTheme'])->name('profile.theme');

    // Foto do aluno — servida diretamente do disco (independe do link public/storage)
    Route::get('/alunos/{aluno}/foto', \App\Http\Controllers\StudentPhotoController::class)->name('alunos.foto');

    Route::middleware('school.member')
        ->prefix('portal')->name('secretaria.')
        ->group(base_path('routes/secretaria.php'));

    Route::middleware('role:professor')
        ->prefix('professor')->name('professor.')
        ->group(base_path('routes/professor.php'));

});

Route::get('/termos', fn() => view('static.termos'))->name('termos');
Route::get('/privacidade', fn() => view('static.privacidade'))->name('privacidade');
Route::get('/suporte', fn() => view('static.suporte'))->name('suporte');

Route::prefix('superadmin')->withoutMiddleware([\Illuminate\Auth\Middleware\Authenticate::class])->group(function () {
    Route::get('/', fn() => redirect()->route('login', ['perfil' => 'escola']))->name('admin.login');
    Route::get('/login', fn() => redirect()->route('login', ['perfil' => 'escola']));

    // Desafio 2FA (login com senha correta, aguardando o código) — sem admin.auth
    Route::get('/2fa',  [\App\Http\Controllers\Admin\TwoFactorChallengeController::class, 'show'])->name('admin.2fa');
    Route::post('/2fa', [\App\Http\Controllers\Admin\TwoFactorChallengeController::class, 'verify'])->middleware('throttle:6,1')->name('admin.2fa.verify');

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\LoginController::class, 'destroy'])->name('admin.logout');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, '__invoke'])->middleware('admin.can:dashboard')->name('admin.dashboard');

        // Segurança da própria conta (2FA) — qualquer admin autenticado
        Route::get('/seguranca',            [\App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('admin.security');
        Route::post('/seguranca/ativar',    [\App\Http\Controllers\Admin\SecurityController::class, 'enable'])->name('admin.security.enable');
        Route::post('/seguranca/desativar', [\App\Http\Controllers\Admin\SecurityController::class, 'disable'])->name('admin.security.disable');

        Route::middleware('admin.can:escolas')->group(function () {
            Route::resource('schools', \App\Http\Controllers\Admin\SchoolController::class)->names('admin.schools');
            Route::post('schools/{school}/reset-password/{user}', [\App\Http\Controllers\Admin\SchoolController::class, 'resetPassword'])->name('admin.schools.resetPassword');
            Route::put('schools/{school}/terminologias', [\App\Http\Controllers\Admin\SchoolController::class, 'updateTerminologias'])->name('admin.schools.terminologias.update');
            Route::put('schools/{school}/campos-obrigatorios', [\App\Http\Controllers\Admin\SchoolController::class, 'updateCamposObrigatorios'])->name('admin.schools.campos-obrigatorios.update');
            // Matérias (gerenciadas pelo Super Admin por escola)
            Route::post('schools/{school}/materias',                  [\App\Http\Controllers\Admin\SchoolSubjectController::class, 'store'])->name('admin.schools.materias.store');
            Route::put('schools/{school}/materias/{subject}',         [\App\Http\Controllers\Admin\SchoolSubjectController::class, 'update'])->name('admin.schools.materias.update');
            Route::delete('schools/{school}/materias/{subject}',      [\App\Http\Controllers\Admin\SchoolSubjectController::class, 'destroy'])->name('admin.schools.materias.destroy');

            // Importação de alunos (exclusiva do superadmin, por escola)
            Route::get('schools/{school}/importar',            [\App\Http\Controllers\Admin\StudentImportController::class, 'index'])->name('admin.schools.import');
            Route::get('schools/{school}/importar/modelo',     [\App\Http\Controllers\Admin\StudentImportController::class, 'template'])->name('admin.schools.import.template');
            Route::post('schools/{school}/importar/preview',   [\App\Http\Controllers\Admin\StudentImportController::class, 'preview'])->name('admin.schools.import.preview');
            Route::post('schools/{school}/importar/confirmar', [\App\Http\Controllers\Admin\StudentImportController::class, 'confirm'])->name('admin.schools.import.confirm');
        });

        Route::middleware('admin.can:financeiro')->group(function () {
            Route::get('financeiro',                    [\App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('admin.invoices.index');
            Route::post('financeiro',                   [\App\Http\Controllers\Admin\InvoiceController::class, 'store'])->name('admin.invoices.store');
            Route::post('financeiro/gerar',             [\App\Http\Controllers\Admin\InvoiceController::class, 'generate'])->name('admin.invoices.generate');
            Route::get('financeiro/{invoice}/pdf',      [\App\Http\Controllers\Admin\InvoiceController::class, 'pdf'])->name('admin.invoices.pdf');
            Route::post('financeiro/{invoice}/pagar',   [\App\Http\Controllers\Admin\InvoiceController::class, 'markPaid'])->name('admin.invoices.pay');
            Route::post('financeiro/{invoice}/cancelar',[\App\Http\Controllers\Admin\InvoiceController::class, 'cancel'])->name('admin.invoices.cancel');
            Route::delete('financeiro/{invoice}',       [\App\Http\Controllers\Admin\InvoiceController::class, 'destroy'])->name('admin.invoices.destroy');
        });

        Route::middleware('admin.can:comunicados')->group(function () {
            Route::get('comunicados',                 [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('admin.announcements.index');
            Route::post('comunicados',                [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('admin.announcements.store');
            Route::put('comunicados/{comunicado}',    [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('admin.announcements.update');
            Route::post('comunicados/{comunicado}/toggle', [\App\Http\Controllers\Admin\AnnouncementController::class, 'toggle'])->name('admin.announcements.toggle');
            Route::delete('comunicados/{comunicado}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
        });

        Route::middleware('admin.can:relatorios')->group(function () {
            Route::get('relatorios', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
        });

        Route::middleware('admin.can:administradores')->group(function () {
            Route::get('administradores',                  [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admin.admins.index');
            Route::post('administradores',                 [\App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('admin.admins.store');
            Route::put('administradores/{administrador}',  [\App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('admin.admins.update');
            Route::delete('administradores/{administrador}',[\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('admin.admins.destroy');
        });

        Route::middleware('admin.can:logs')->group(function () {
            Route::get('logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('admin.logs.index');
        });
    });
});