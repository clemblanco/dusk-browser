<?php

namespace DuskScraper;

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
            __DIR__ . '/../config/dusk-scraper.php' => config_path('dusk-scraper.php'),
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
            __DIR__ . '/../config/dusk-scraper.php', 'dusk-scraper'
        );

        $this->app->singleton(DuskScraper::class, function () {
            return new DuskScraper;
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\ChromeDriverCommand::class,
            ]);
        }
    }
}
