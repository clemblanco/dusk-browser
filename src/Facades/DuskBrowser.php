<?php

namespace DuskBrowser\Facades;

/**
 * @method static void browse(\Closure $callback)
 * @method static void closeAll()
 *
 * @see \DuskBrowser\DuskBrowser
 */
class DuskBrowser extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return \DuskBrowser\DuskBrowser::class;
    }
}
