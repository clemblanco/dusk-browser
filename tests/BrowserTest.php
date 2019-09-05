<?php

namespace DuskScraper\Tests;

use stdClass;
use Mockery as m;
use DuskScraper\Browser;
use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

class BrowserTest extends TestCase
{
    /** @var \Mockery\MockInterface */
    private $driver;

    /** @var Browser */
    private $browser;

    protected function setUp(): void
    {
        $this->driver = m::mock(stdClass::class);

        $this->browser = new Browser($this->driver);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function test_visit()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('navigate->to')->with('http://laravel.dev/login');
        $browser = new Browser($driver);

        $browser->visit('http://laravel.dev/login');
    }

    public function test_refresh_method()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('navigate->refresh')->once();
        $browser = new Browser($driver);

        $browser->refresh();
    }

    public function test_with_method()
    {
        $driver = m::mock(stdClass::class);
        $browser = new Browser($driver);

        $browser->with('prefix', function ($browser) {
            $this->assertInstanceof(Browser::class, $browser);
            $this->assertEquals('body prefix', $browser->resolver->prefix);
        });
    }

    public function test_within_method()
    {
        $driver = m::mock(stdClass::class);
        $browser = new Browser($driver);

        $browser->within('prefix', function ($browser) {
            $this->assertInstanceof(Browser::class, $browser);
            $this->assertEquals('body prefix', $browser->resolver->prefix);
        });
    }

    public function test_retrieve_console()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('manage->getLog')->with('browser')->andReturnNull();
        $driver->shouldReceive('getCapabilities->getBrowserName')->andReturn(WebDriverBrowserType::CHROME);
        $browser = new Browser($driver);
        Browser::$storeConsoleLogAt = 'not-null';

        $browser->storeConsoleLog('file');
    }

    public function test_disable_console()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldNotReceive('manage');
        $driver->shouldReceive('getCapabilities->getBrowserName')->andReturnNull();
        $browser = new Browser($driver);

        $browser->storeConsoleLog('file');
    }

    public function test_screenshot()
    {
        $this->driver->shouldReceive('takeScreenshot')->andReturnUsing(function ($filePath) {
            touch($filePath);
        });

        Browser::$storeScreenshotsAt = sys_get_temp_dir();

        $this->browser->screenshot(
            $name = 'screenshot-01'
        );

        $this->assertFileExists(Browser::$storeScreenshotsAt.'/'.$name.'.png');
    }

    public function test_screenshot_in_subdirectory()
    {
        $this->driver->shouldReceive('takeScreenshot')->andReturnUsing(function ($filePath) {
            touch($filePath);
        });

        Browser::$storeScreenshotsAt = sys_get_temp_dir();

        $this->browser->screenshot(
            $name = uniqid('random').'/sub/dir/screenshot-01'
        );

        $this->assertFileExists(Browser::$storeScreenshotsAt.'/'.$name.'.png');
    }
}
