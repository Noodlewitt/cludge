<?php

namespace Cludge\Providers;

use Illuminate\Support\ServiceProvider;

class CludgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->registerDependencies([
            FormServiceProvider::class,
            CommandsServiceProvider::class,
            EloquentServiceProvider::class,
            PageServiceProvider::class,
            MenuServiceProvider::class,
            RapydServiceProvider::class,
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
