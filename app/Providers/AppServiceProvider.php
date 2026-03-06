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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Los eventos y listeners NO se registran aquí manualmente porque
     * Laravel 12 tiene auto-descubrimiento activado por defecto:
     * detecta automáticamente que SendProductCreatedEmail escucha
     * ProductCreated gracias al tipo del parámetro en su método handle().
     */
    public function boot(): void
    {
        //
    }
}
