<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secretaria\AllDocumentsController;

Route::get('/', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::middleware(['auth', 'school.active'])->group(function () {
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');

    Route::middleware('role:secretaria')
        ->prefix('secretaria')->name('secretaria.')
        ->group(base_path('routes/secretaria.php'));

    Route::middleware('role:professor')
        ->prefix('professor')->name('professor.')
        ->group(base_path('routes/professor.php'));

    Route::middleware('role:pai')
        ->prefix('responsavel')->name('pai.')
        ->group(base_path('routes/pai.php'));
});

Route::prefix('superadmin')->withoutMiddleware([\Illuminate\Auth\Middleware\Authenticate::class])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\LoginController::class, 'create'])->name('admin.login');
    Route::get('/login', [\App\Http\Controllers\Admin\LoginController::class, 'create']);
    Route::post('/login', [\App\Http\Controllers\Admin\LoginController::class, 'store'])->name('admin.login.store');

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\LoginController::class, 'destroy'])->name('admin.logout');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, '__invoke'])->name('admin.dashboard');
        Route::resource('schools', \App\Http\Controllers\Admin\SchoolController::class)->names('admin.schools');
        Route::post('schools/{school}/reset-password/{user}', [\App\Http\Controllers\Admin\SchoolController::class, 'resetPassword'])->name('admin.schools.resetPassword');
    });
Route::get('/perfil', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/perfil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::get('secretaria/documentos', [AllDocumentsController::class, 'index']);
Route::get('documentos/{documento}/pdf', [\App\Http\Controllers\Pai\DocumentPdfController::class, '__invoke'])
    ->name('documentos.pdf');
});