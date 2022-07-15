<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Email extends Facade
{
    /**
     * Return the facade accessor
     *
     * @return  string
     */
    protected static function getFacadeAccessor()
    {
        return 'email';
    }
}
