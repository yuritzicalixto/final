<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
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
     */
    public function boot(): void
    {
        //
        User::observe(UserObserver::class);

        // Fix OpenSSL para XAMPP en Windows
    // Necesario para que web-push pueda encriptar las notificaciones
    if (PHP_OS_FAMILY === 'Windows') {
        $opensslConf = 'C:\\xampp\\apache\\conf\\openssl.cnf';
        if (file_exists($opensslConf)) {
            putenv("OPENSSL_CONF={$opensslConf}");
        }
    }
    }
}
