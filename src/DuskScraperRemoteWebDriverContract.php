<?php

namespace DuskScraper;

use Facebook\WebDriver\Remote\RemoteWebDriver;

interface DuskScraperRemoteWebDriverContract
{
    /**
     * @return RemoteWebDriver
     */
    public function __invoke(): RemoteWebDriver;
}
