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

        if ( ! $this->app->routesAreCached()) {
            $this->app['router']->get(config('gluu-wrapper.route_endpoint'), function () {
                return redirect($this->app['gluu-wrapper']->generateURI());
            });

            $this->app['router']->get(config('gluu-wrapper.route_get_user_info'), function ($access_token) {
                $userInfoJWE = $this->app['gluu-wrapper.user-info']->getUserInfo($access_token);

                return response()->json($userInfoJWE);
            });

            $this->app['router']->get(config('gluu-wrapper.route_access_token_granted'), function () {
                $request = $this->app['gluu-wrapper']->getRequest($this->app['request']);

                if (isset($request['code'])) {
                    $accessToken = $this->app['gluu-wrapper']->getAccessToken($request['code'], $request['state']);

                    return response()->json($accessToken);
                }

                return response()->json([ 'error' => 404, 'message' => 'Error' ]);
            });
        }
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

        $this->app->singleton('gluu-wrapper.user-info', function($app) {
            return new UserInfoRequester;
        });

        $this->app->singleton('gluu-wrapper', function($app) {
            return $app->make(Contract::class);
        });
    }
}
