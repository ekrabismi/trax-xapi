<?php

namespace Trax\XapiServer\Routes;

class XapiActivityRoutes extends XapiRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Activity';
    
    /**
     * Route name.
     */
    protected $routeName = 'xapi.activities';

    /**
     * Only these API functions.
     */
    protected $only = array('get', 'store');

}
