<?php

namespace Trax\XapiServer\Routes;

use Trax\DataStore\Routes\DataRoutes;

class AgentRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Agent';
    
    /**
     * Only these API functions.
     */
    protected $only = array('get', 'store');


}
