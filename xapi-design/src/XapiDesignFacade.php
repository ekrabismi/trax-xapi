<?php

namespace Trax\XapiDesign;

use Illuminate\Support\Facades\Facade;

class XapiDesignFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\XapiDesign\XapiDesignServices';
    }

}
