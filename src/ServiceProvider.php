<?php

namespace Refactory\LaravelGluuWrapper;

use Illuminate\Support\ServiceProvider as BaseProvider;
use Refactory\LaravelGluuWrapper\Contracts\TokenRequester as Contract;

class ServiceProvider extends BaseProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/gluu-wrapper.php' => config_path('gluu-wrapper.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/gluu-wrapper.php', 'gluu-wrapper'
        );

        $this->app->singleton(Contract::class, TokenRequester::class);
    }
}
