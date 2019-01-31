<?php

namespace Trax\XapiServer\Routes;

class XapiActivityProfileRoutes extends XapiRoutes
{
    /**
     * The data model.
     */
    protected $model = 'ActivityProfile';
    
    /**
     * Route name.
     */
    protected $routeName = 'xapi.activities.profile';

    /**
     * Only these API functions.
     */
    protected $only = array('get', 'store', 'update', 'delete');


    /**
     * Construct.
     */
    public function __construct($plugin, $namespace, $config)
    {
        parent::__construct($plugin, $namespace, $config);
        $this->methods['update'] = ['http' => 'put', 'route' => ''];
        $this->methods['delete'] = ['http' => 'delete', 'route' => ''];
    }
    

}
