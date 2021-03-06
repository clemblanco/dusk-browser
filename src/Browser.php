<?php

namespace DuskBrowser;

use Closure;
use Exception;
use BadMethodCallException;
use Illuminate\Support\Str;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverPoint;
use Illuminate\Support\Traits\Macroable;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

class Browser
{
    use Concerns\InteractsWithCookies,
        Concerns\InteractsWithElements,
        Concerns\InteractsWithJavascript,
        Concerns\InteractsWithMouse,
        Concerns\WaitsForElements,
        Macroable {
            __call as macroCall;
        }

    /**
     * The directory that will contain any screenshots.
     *
     * @var string
     */
    public static $storeScreenshotsAt;

    /**
     * The directory that will contain any console logs.
     *
     * @var string
     */
    public static $storeConsoleLogAt;

    /**
     * The browsers that support retrieving logs.
     *
     * @var array
     */
    public static $supportsRemoteLogs = [
        WebDriverBrowserType::CHROME,
        WebDriverBrowserType::PHANTOMJS,
    ];

    /**
     * The default wait time in seconds.
     *
     * @var int
     */
    public static $waitSeconds = 5;

    /**
     * The RemoteWebDriver instance.
     *
     * @var \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    public $driver;

    /**
     * The element resolver instance.
     *
     * @var ElementResolver
     */
    public $resolver;

    /**
     * Create a browser instance.
     *
     * @param  \Facebook\WebDriver\Remote\RemoteWebDriver  $driver
     * @param  ElementResolver  $resolver
     * @return void
     */
    public function __construct($driver, $resolver = null)
    {
        $this->driver = $driver;

        $this->resolver = $resolver ?: new ElementResolver($driver);
    }

    /**
     * Browse to the given URL.
     *
     * @param string $url
     *
     * @return $this
     * @throws Exception
     */
    public function visit($url)
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            throw new Exception('URL should start with http:// or https://');
        }

        $this->driver->navigate()->to($url);

        return $this;
    }

    /**
     * Refresh the page.
     *
     * @return $this
     */
    public function refresh()
    {
        $this->driver->navigate()->refresh();

        return $this;
    }

    /**
     * Navigate to the previous page.
     *
     * @return $this
     */
    public function back()
    {
        $this->driver->navigate()->back();

        return $this;
    }

    /**
     * Maximize the browser window.
     *
     * @return $this
     */
    public function maximize()
    {
        $this->driver->manage()->window()->maximize();

        return $this;
    }

    /**
     * Resize the browser window.
     *
     * @param  int  $width
     * @param  int  $height
     * @return $this
     */
    public function resize($width, $height)
    {
        $this->driver->manage()->window()->setSize(
            new WebDriverDimension($width, $height)
        );

        return $this;
    }

    /**
     * Make the browser window as large as the content.
     *
     * @return $this
     */
    public function fitContent()
    {
        $body = $this->driver->findElement(WebDriverBy::tagName('body'));

        if (! empty($body)) {
            $this->resize($body->getSize()->getWidth(), $body->getSize()->getHeight());
        }

        return $this;
    }

    /**
     * Move the browser window.
     *
     * @param  int  $x
     * @param  int  $y
     * @return $this
     */
    public function move($x, $y)
    {
        $this->driver->manage()->window()->setPosition(
            new WebDriverPoint($x, $y)
        );

        return $this;
    }

    /**
     * Take a screenshot and store it with the given name.
     *
     * @param  string  $name
     * @return $this
     */
    public function screenshot($name)
    {
        $filePath = sprintf('%s/%s.png', rtrim(static::$storeScreenshotsAt, '/'), $name);

        $directoryPath = dirname($filePath);

        if (! is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $this->driver->takeScreenshot($filePath);

        return $this;
    }

    /**
     * Store the console output with the given name.
     *
     * @param  string  $name
     * @return $this
     */
    public function storeConsoleLog($name)
    {
        if (in_array($this->driver->getCapabilities()->getBrowserName(), static::$supportsRemoteLogs)) {
            $console = $this->driver->manage()->getLog('browser');

            if (! empty($console)) {
                file_put_contents(
                    sprintf('%s/%s.log', rtrim(static::$storeConsoleLogAt, '/'), $name), json_encode($console, JSON_PRETTY_PRINT)
                );
            }
        }

        return $this;
    }

    /**
     * Switch to a specified frame in the browser and execute the given callback.
     *
     * @param  string  $selector
     * @param  \Closure  $callback
     * @return $this
     */
    public function withinFrame($selector, Closure $callback)
    {
        $this->driver->switchTo()->frame($this->resolver->findOrFail($selector));

        $callback($this);

        $this->driver->switchTo()->defaultContent();

        return $this;
    }

    /**
     * Execute a Closure with a scoped browser instance.
     *
     * @param  string  $selector
     * @param  \Closure  $callback
     * @return $this
     */
    public function within($selector, Closure $callback)
    {
        return $this->with($selector, $callback);
    }

    /**
     * Execute a Closure with a scoped browser instance.
     *
     * @param  string  $selector
     * @param  \Closure  $callback
     * @return $this
     */
    public function with($selector, Closure $callback)
    {
        $browser = new static(
            $this->driver, new ElementResolver($this->driver, $this->resolver->format($selector))
        );

        call_user_func($callback, $browser);

        return $this;
    }

    /**
     * Ensure that jQuery is available on the page.
     *
     * @return void
     */
    public function ensurejQueryIsAvailable()
    {
        if ($this->driver->executeScript('return window.jQuery == null')) {
            $this->driver->executeScript(file_get_contents(__DIR__.'/../bin/jquery.js'));
        }
    }

    /**
     * Pause for the given amount of milliseconds.
     *
     * @param  int  $milliseconds
     * @return $this
     */
    public function pause($milliseconds)
    {
        usleep($milliseconds * 1000);

        return $this;
    }

    /**
     * Close the browser.
     *
     * @return void
     */
    public function quit()
    {
        $this->driver->quit();
    }

    /**
     * Tap the browser into a callback.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function tap($callback)
    {
        $callback($this);

        return $this;
    }

    /**
     * Dump the content from the last response.
     *
     * @return void
     */
    public function dump()
    {
        dd($this->driver->getPageSource());
    }

    /**
     * Pause execution of test and open Laravel Tinker (PsySH) REPL.
     *
     * @return $this
     */
    public function tinker()
    {
        \Psy\Shell::debug([
            'browser' => $this,
            'driver' => $this->driver,
            'resolver' => $this->resolver,
        ], $this);

        return $this;
    }

    /**
     * Stop running tests but leave the browser open.
     *
     * @return void
     */
    public function stop()
    {
        exit();
    }

    /**
     * Dynamically call a method on the browser.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        throw new BadMethodCallException("Call to undefined method [{$method}].");
    }
}
