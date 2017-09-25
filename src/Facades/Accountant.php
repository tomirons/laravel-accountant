<?php

namespace TomIrons\Accountant\Facades;

use Illuminate\Support\Facades\Facade;
use TomIrons\Accountant\Accountant as AccountantHelper;

class Accountant extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return AccountantHelper::class;
    }
}