<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Invokable class to instantiate a Remote Web Driver
    |--------------------------------------------------------------------------
    |
    | By default, Dusk Scraper uses Google Chrome browser and a standalone
    | ChromeDriver installation. However, you may use any remote web
    | driver supported by Facebook WebDriver, like a Selenium server
    | with a Firefox or PhantomJS browser.
    |
    */

    'remote_web_driver' => \DuskScraper\ChromeRemoteWebDriver::class,

    'remote_web_driver_url' => 'http://localhost:9515',

];
