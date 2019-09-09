<?php

namespace DuskBrowser;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/dusk-browser.php' => config_path('dusk-browser.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     * @throws \Exception
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/dusk-browser.php', 'dusk-browser'
        );

        $this->app->singleton(DuskBrowser::class, function () {
            return new DuskBrowser;
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\ChromeDriverCommand::class,
            ]);
        }
    }
}
