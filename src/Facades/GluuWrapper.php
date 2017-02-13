<?php

namespace Refactory\LaravelGluuWrapper\Facades;

use Refactory\LaravelGluuWrapper\Contracts\TokenRequester;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Routing\Router
 */
class GluuWrapper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TokenRequester::class;
    }
}
