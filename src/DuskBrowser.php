<?php

namespace DuskBrowser;

use DuskBrowser\Chrome\SupportsChrome;
use DuskBrowser\Concerns\ProvidesBrowser;

class DuskBrowser
{
    use ProvidesBrowser, SupportsChrome;

    /**
     * DuskBrowser constructor. Only called once as we're using a singleton.
     *
     * @return void
     */
    public function __construct()
    {
        Browser::$storeScreenshotsAt = storage_path('app/dusk-browser/screenshots');

        Browser::$storeConsoleLogAt = storage_path('app/dusk-browser/console');

        if (config('dusk-browser.remote_web_driver_url') === 'http://localhost:9515') {
            static::startChromeDriver();
        }
    }

    /**
     * DuskBrowser destructor. Called whenever the application instance gets killed.
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
        $remoteWebDriverClass = config('dusk-browser.remote_web_driver');

        $remoteWebDriver = new $remoteWebDriverClass;

        return $remoteWebDriver();
    }
}
