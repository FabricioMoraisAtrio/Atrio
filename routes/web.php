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

// Executa migrations pendentes via URL (hospedagem compartilhada sem SSH).
// Exige token NÃO-VAZIO definido em CRON_TOKEN, para não ficar exposta.
Route::get('/cron/migrate', function () {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    \Artisan::call('migrate', ['--force' => true]);

    return response('<pre>' . e(\Artisan::output()) . '</pre>');
});

// Mostra o status das migrations (quais já rodaram / pendentes). Mesmo token.
Route::get('/cron/migrate-status', function () {
    $token = config('app.cron_token');
    abort_if(! $token || request('token') !== $token, 403);

    \Artisan::call('migrate:status');

    return response('<pre>' . e(\Artisan::output()) . '</pre>');
});
Route::get('/', fn() => view('landing'))->name('home');
Route::get('/entrar', [LoginController::class, 'create'])->name('login');
Route::post('/entrar', [LoginController::class, 'store'])->name('login.store');

Route::middleware(['auth', 'school.active'])->group(function () {
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');

    Route::get('/perfil', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

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
    });
});