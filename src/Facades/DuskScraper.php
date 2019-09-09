<?php

namespace DuskScraper\Facades;

/**
 * @method static void browse(\Closure $callback)
 * @method static void closeAll()
 *
 * @see \DuskScraper\DuskScraper
 */
class DuskScraper extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return DuskScraper::class;
    }
}
