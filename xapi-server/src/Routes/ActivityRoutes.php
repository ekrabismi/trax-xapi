<?php

namespace Trax\XapiServer\Routes;

use Trax\DataStore\Routes\DataRoutes;

class ActivityRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Activity';
    
    /**
     * Route name.
     */
    protected $routeName = 'xapi-server.activities';

    /**
     * Only these API functions.
     */
    protected $only = array('get', 'store');


}
