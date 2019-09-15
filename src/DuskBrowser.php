<?php

namespace DuskBrowser;

use DuskBrowser\Chrome\SupportsChrome;
use DuskBrowser\Concerns\ProvidesBrowser;

class DuskBrowser
{
    use ProvidesBrowser, SupportsChrome;

    /**
     * Before the browsing session happens.
     *
     * @return void
     */
    protected function beforeBrowse()
    {
        Browser::$storeScreenshotsAt = storage_path('app/dusk-browser/screenshots');

        Browser::$storeConsoleLogAt = storage_path('app/dusk-browser/console');

        if (config('dusk-browser.remote_web_driver_url') === 'http://localhost:9515') {
            static::startChromeDriver();
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
