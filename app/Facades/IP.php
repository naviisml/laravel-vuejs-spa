<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class IP extends Facade
{
    /**
     * Return the facade accessor
     *
     * @return  string
     */
    protected static function getFacadeAccessor()
    {
        return 'ip';
    }
}
