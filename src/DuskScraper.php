<?php

namespace DuskScraper;

use DuskScraper\Chrome\SupportsChrome;
use DuskScraper\Concerns\ProvidesBrowser;

class DuskScraper
{
    use ProvidesBrowser, SupportsChrome;

    /**
     * DuskScraper constructor. Only called once as we're using a singleton.
     *
     * @return void
     */
    public function __construct()
    {
        Browser::$storeScreenshotsAt = storage_path('app/dusk-scraper/screenshots');

        Browser::$storeConsoleLogAt = storage_path('app/dusk-scraper/console');

        if (config('dusk-scraper.remote_web_driver_url') === 'http://localhost:9515') {
            static::startChromeDriver();
        }
    }

    /**
     * DuskScraper destructor. Called whenever the application instance gets killed.
     *
     * @return void
     */
    public function __destruct()
    {
        static::closeAll();

        foreach (static::$afterBrowseCallbacks as $callback) {
            $callback();
        }
    }

    /**
     * Create the RemoteWebDriver instance using an invokable class.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $remoteWebDriverClass = config('dusk-scraper.remote_web_driver');

        $remoteWebDriver = new $remoteWebDriverClass;

        return $remoteWebDriver();
    }
}
