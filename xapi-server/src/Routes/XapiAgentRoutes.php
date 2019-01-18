<?php

namespace Trax\XapiServer\Routes;

class XapiAgentRoutes extends XapiRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Agent';
    
    /**
     * Route name.
     */
    protected $routeName = 'xapi.agents';

    /**
     * Only these API functions.
     */
    protected $only = array('get', 'store');


}
