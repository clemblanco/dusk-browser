<?php

namespace DuskBrowser\Concerns;

use Closure;
use Exception;
use Throwable;
use ReflectionFunction;
use DuskBrowser\Browser;
use Illuminate\Support\Collection;

trait ProvidesBrowser
{
    /**
     * All of the active browser instances.
     *
     * @var array
     */
    protected static $browsers = [];

    /**
     * The callbacks that should be run after browsing.
     *
     * @var array
     */
    protected static $afterBrowseCallbacks = [];

    /**
     * Register an "after browse" tear down callback.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function afterBrowse(Closure $callback)
    {
        static::$afterBrowseCallbacks[] = $callback;
    }

    /**
     * Create a new browser instance.
     *
     * @param  \Closure  $callback
     * @return \DuskBrowser\Browser|void
     * @throws \Exception
     * @throws \Throwable
     */
    public function browse(Closure $callback)
    {
        $browsers = $this->createBrowsersFor($callback);

        try {
            $callback(...$browsers->all());
        } catch (Exception $e) {
            $this->captureFailuresFor($browsers);

            throw $e;
        } catch (Throwable $e) {
            $this->captureFailuresFor($browsers);

            throw $e;
        } finally {
            $this->storeConsoleLogsFor($browsers);

            $this->closeAll();
        }
    }

    /**
     * Create the browser instances needed for the given callback.
     *
     * @param  \Closure  $callback
     * @return array
     * @throws \ReflectionException
     */
    protected function createBrowsersFor(Closure $callback)
    {
        if (count(static::$browsers) === 0) {
            static::$browsers = collect([$this->newBrowser($this->createWebDriver())]);
        }

        $additional = $this->browsersNeededFor($callback) - 1;

        for ($i = 0; $i < $additional; $i++) {
            static::$browsers->push($this->newBrowser($this->createWebDriver()));
        }

        return static::$browsers;
    }

    /**
     * Create a new Browser instance.
     *
     * @param  \Facebook\WebDriver\Remote\RemoteWebDriver  $driver
     * @return \DuskBrowser\Browser
     */
    protected function newBrowser($driver)
    {
        return new Browser($driver);
    }

    /**
     * Get the number of browsers needed for a given callback.
     *
     * @param  \Closure  $callback
     * @return int
     * @throws \ReflectionException
     */
    protected function browsersNeededFor(Closure $callback)
    {
        return (new ReflectionFunction($callback))->getNumberOfParameters();
    }

    /**
     * Capture failure screenshots for each browser.
     *
     * @param  \Illuminate\Support\Collection  $browsers
     * @return void
     */
    protected function captureFailuresFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            /** @var Browser $browser */
            $host = parse_url($browser->driver->getCurrentURL(), PHP_URL_HOST);
            $host = str_replace('.', '_', $host);
            $timestamp = date('Y_m_d_His');

            $browser->screenshot("failure-$host-$key-$timestamp");
        });
    }

    /**
     * Store the console output for the given browsers.
     *
     * @param  \Illuminate\Support\Collection  $browsers
     * @return void
     */
    protected function storeConsoleLogsFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            /** @var Browser $browser */
            $host = parse_url($browser->driver->getCurrentURL(), PHP_URL_HOST);
            $host = str_replace('.', '_', $host);
            $timestamp = date('Y_m_d_His');

            $browser->storeConsoleLog("$host-$key-$timestamp");
        });
    }

    /**
     * Close all of the active browsers.
     *
     * @return void
     */
    public static function closeAll()
    {
        Collection::make(static::$browsers)->each->quit();

        static::$browsers = collect();
    }

    /**
     * Create the remote web driver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     * @throws \Exception
     */
    protected function createWebDriver()
    {
        return retry(5, function () {
            return $this->driver();
        }, 50);
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    abstract protected function driver();
}
