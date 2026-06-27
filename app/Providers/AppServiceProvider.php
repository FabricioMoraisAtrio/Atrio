<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sistema é PT-BR: força o locale para as mensagens (validação, etc.)
        // ficarem em português, independentemente do APP_LOCALE do ambiente.
        $this->app->setLocale('pt_BR');
    }
}
