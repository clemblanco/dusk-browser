<?php

namespace DuskScraper;

use Illuminate\Support\ServiceProvider;

class DuskScraperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     * @throws \Exception
     */
    public function register()
    {
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
