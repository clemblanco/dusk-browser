<?php

namespace DuskScraper;

use DuskScraper\Chrome\SupportsChrome;
use DuskScraper\Concerns\ProvidesBrowser;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

class DuskScraper
{
    use ProvidesBrowser, SupportsChrome;

    /**
     * DuskScraper constructor.
     */
    public function __construct()
    {
        if ($this->driver()->getCapabilities()->getBrowserName() === WebDriverBrowserType::CHROME) {
            static::startChromeDriver();
        }
    }

    /**
     * DuskScraper destructor.
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
