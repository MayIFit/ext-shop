<?php

namespace MayIFit\Core\Translation\Facades;

use Illuminate\Support\Facades\Facade;

class Translation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'translation';
    }
}