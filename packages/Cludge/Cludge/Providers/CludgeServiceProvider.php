<?php

namespace Cludge\Providers;

use Illuminate\Support\ServiceProvider;
use Cludge\Helpers\Helpers;

class CludgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //we publish this to the default namespace so I don't have to re-do all the login controller stuff - wish laravel
        //added a way to customise the route namespace.
        $this->app['view']->addLocation(__DIR__.'/../Views/');

        //publish a sensible default config if it's not there.
        $this->publishes([
            __DIR__.'/../Config/cludge.php' => config_path('cludge.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('Helpers', function($app)
        {
            return new Helpers;
        });

        $this->registerDependencies([
            AuthServiceProvider::class,
            RouteServiceProvider::class,
        ]);
    }


    /**
     * Register dependencies
     *
     * @param array $services
     */
    protected function registerDependencies(array $services)
    {
        foreach ($services as $service) {
            $this->app->register($service);
        }
    }
    /**
     * Register facades
     *
     * @param array $facades
     */
    protected function registerFacades(array $facades)
    {
        foreach ($facades as $facade => $class) {
            AliasLoader::getInstance()->alias($facade, $class);
        }
    }
}
