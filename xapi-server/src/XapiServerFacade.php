<?php

namespace Trax\XapiServer;

use Illuminate\Support\Facades\Facade;

class XapiServerFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\XapiServer\XapiServerServices';
    }

}
