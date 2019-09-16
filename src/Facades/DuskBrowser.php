<?php

namespace DuskBrowser\Facades;

/**
 * @method static void browse(\Closure $callback)
 * @method static void quit()
 *
 * @see \DuskBrowser\DuskBrowser
 */
class DuskBrowser extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return \DuskBrowser\DuskBrowser::class;
    }
}
