<?php

namespace DuskScraper;

class DuskScraper
{
    /**
     * Register the DuskScraper service provider.
     *
     * @return void
     */
    public static function register()
    {
        app()->register(DuskScraperServiceProvider::class);
    }
}
