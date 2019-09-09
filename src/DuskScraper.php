<?php

namespace DuskScraper;

use DuskScraper\Chrome\SupportsChrome;
use DuskScraper\Concerns\ProvidesBrowser;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

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
        if ($this->driver()->getCapabilities()->getBrowserName() === WebDriverBrowserType::CHROME) {
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
        $remoteWebDriver = config('dusk-scraper.remote_web_driver');

        return (new $remoteWebDriver);
    }
}
