<?php
// app/Providers/MiClaseServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Migas;

class MigasServiceProvider extends ServiceProvider
{
    /**
     * Registra servicios de la aplicación.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('migas', function ($app) {
            return new Migas();
        });
    }

    /**
     * Realiza tareas de inicio después de que la aplicación se ha arrancado.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
