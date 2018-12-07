<?php

namespace Tomato\OmiPay;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return OmiPay::class;
    }
}