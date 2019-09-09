<?php

namespace DuskBrowser\Contracts;

use Facebook\WebDriver\Remote\RemoteWebDriver;

interface RemoteWebDriverContract
{
    /**
     * @return RemoteWebDriver
     */
    public function __invoke();
}
