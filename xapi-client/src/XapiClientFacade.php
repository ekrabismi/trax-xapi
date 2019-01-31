<?php

namespace Trax\XapiClient;

use Illuminate\Support\Facades\Facade;

class XapiClientFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\XapiClient\XapiClientServices';
    }

}
