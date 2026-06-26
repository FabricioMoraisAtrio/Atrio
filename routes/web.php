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

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Link de redefinição enviado para seu e-mail.')
        : back()->withErrors(['email' => 'Não encontramos um usuário com este e-mail.']);
})->name('password.email');

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
})->name('password.update');


Route::get('/cron/notificacoes', function () {
    if (request('token') !== config('app.cron_token')) {
        abort(403);
    }
    \Artisan::call('atrio:notificacoes-diarias');
    return response()->json(['ok' => true]);
});

// Cabeçalhos para impedir cache das respostas /cron/* (HostGator/LiteSpeed
// costuma cachear GET, o que confunde o diagnóstico).
$noStore = fn ($response) => $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

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

Route::get('/', fn() => view('landing'))->name('home');
Route::get('/entrar', [LoginController::class, 'create'])->name('login');
Route::post('/entrar', [LoginController::class, 'store'])->name('login.store');

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

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\LoginController::class, 'destroy'])->name('admin.logout');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, '__invoke'])->name('admin.dashboard');
        Route::resource('schools', \App\Http\Controllers\Admin\SchoolController::class)->names('admin.schools');
        Route::post('schools/{school}/reset-password/{user}', [\App\Http\Controllers\Admin\SchoolController::class, 'resetPassword'])->name('admin.schools.resetPassword');
        Route::put('schools/{school}/terminologias', [\App\Http\Controllers\Admin\SchoolController::class, 'updateTerminologias'])->name('admin.schools.terminologias.update');
        // Matérias (gerenciadas pelo Super Admin por escola)
        Route::post('schools/{school}/materias',                  [\App\Http\Controllers\Admin\SchoolSubjectController::class, 'store'])->name('admin.schools.materias.store');
        Route::put('schools/{school}/materias/{subject}',         [\App\Http\Controllers\Admin\SchoolSubjectController::class, 'update'])->name('admin.schools.materias.update');
        Route::delete('schools/{school}/materias/{subject}',      [\App\Http\Controllers\Admin\SchoolSubjectController::class, 'destroy'])->name('admin.schools.materias.destroy');

        // Financeiro (faturas)
        Route::get('financeiro',                    [\App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('admin.invoices.index');
        Route::post('financeiro',                   [\App\Http\Controllers\Admin\InvoiceController::class, 'store'])->name('admin.invoices.store');
        Route::post('financeiro/gerar',             [\App\Http\Controllers\Admin\InvoiceController::class, 'generate'])->name('admin.invoices.generate');
        Route::post('financeiro/{invoice}/pagar',   [\App\Http\Controllers\Admin\InvoiceController::class, 'markPaid'])->name('admin.invoices.pay');
        Route::post('financeiro/{invoice}/cancelar',[\App\Http\Controllers\Admin\InvoiceController::class, 'cancel'])->name('admin.invoices.cancel');
        Route::delete('financeiro/{invoice}',       [\App\Http\Controllers\Admin\InvoiceController::class, 'destroy'])->name('admin.invoices.destroy');
    });
});