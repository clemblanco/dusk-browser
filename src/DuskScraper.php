<?php

namespace DuskScraper;

use DuskScraper\Chrome\SupportsChrome;
use DuskScraper\Concerns\ProvidesBrowser;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

class DuskScraper
{
    use ProvidesBrowser, SupportsChrome;

    public function __construct()
    {
        static::startChromeDriver();
    }

    public function __destruct()
    {
        static::closeAll();

        foreach (static::$afterBrowseCallbacks as $callback) {
            $callback();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
