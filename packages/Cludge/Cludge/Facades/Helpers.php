<?php

namespace Cludge\Facades;

use Illuminate\Support\Facades\Facade;

class Helpers extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return (string)
     */
    protected static function getFacadeAccessor() { return \Cludge\Helpers\Helpers::class; }
}