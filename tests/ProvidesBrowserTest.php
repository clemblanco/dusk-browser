<?php

namespace DuskBrowser\Tests;

use stdClass;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use DuskBrowser\Concerns\ProvidesBrowser;

class ProvidesBrowserTest extends TestCase
{
    use ProvidesBrowser;

    /**
     * @dataProvider testData
     */
    public function test_capture_failures_for()
    {
        $browser = m::mock(stdClass::class);
        $browser->driver = m::mock(stdClass::class)
            ->shouldReceive('getCurrentURL')
            ->andReturn('https://www.google.com')
            ->getMock();
        $timestamp = date('Y_m_d_His');
        $browser->shouldReceive('screenshot')->with(
            "failure-www_google_com-0-$timestamp"
        );
        $browsers = collect([$browser]);

        $this->captureFailuresFor($browsers);
    }

    /**
     * @dataProvider testData
     */
    public function test_store_console_logs_for()
    {
        $browser = m::mock(stdClass::class);
        $browser->driver = m::mock(stdClass::class)
            ->shouldReceive('getCurrentURL')
            ->andReturn('https://www.google.com')
            ->getMock();
        $timestamp = date('Y_m_d_His');
        $browser->shouldReceive('storeConsoleLog')->with(
            "www_google_com-0-$timestamp"
        );
        $browsers = collect([$browser]);

        $this->storeConsoleLogsFor($browsers);
    }

    public function testData()
    {
        return [
            ['foo'],
        ];
    }

    /**
     * Implementation of abstract ProvidesBrowser::driver().
     */
    protected function driver()
    {
        //
    }

    /**
     * Before the browsing session happens.
     *
     * @return void
     */
    protected function prepare()
    {
        //
    }
}
