<?php

namespace DefaultTheme\Providers;

use Illuminate\Support\ServiceProvider;

class DefaultThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'default_theme');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

}
