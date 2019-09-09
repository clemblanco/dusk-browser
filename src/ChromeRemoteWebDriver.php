<?php

namespace DuskBrowser;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use DuskBrowser\Contracts\RemoteWebDriverContract;

class ChromeRemoteWebDriver implements RemoteWebDriverContract
{
    /**
     * Instantiate the default Remote Web Driver for Google Chrome browser
     * and ChromeDriver browser webdriver.
     *
     * @return RemoteWebDriver
     */
    public function __invoke()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            config('dusk-browser.remote_web_driver_url'), DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
