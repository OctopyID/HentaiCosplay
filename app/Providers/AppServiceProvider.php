<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws Exception
     */
    public function boot() : void
    {
        // check if required extensions are loaded
        foreach (['curl', 'mbstring'] as $extension) {
            if (! extension_loaded($extension)) {
                throw new Exception(sprintf("%s extension is not loaded.", $extension));
            }
        }

        // set sqlite database path
        if ($this->app->environment('production')) {
            config([
                'database.connections.sqlite.database' => home_path('database.sqlite'),
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        //
    }
}
