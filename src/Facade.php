<?php

namespace DuskScraper;

/**
 * @method static void browse(\Closure $callback)
 * @method static void closeAll()
 *
 * @see \DuskScraper\DuskScraper
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return DuskScraper::class;
    }
}
