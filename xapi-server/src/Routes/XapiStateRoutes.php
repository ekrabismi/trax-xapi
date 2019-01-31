<?php

namespace Trax\XapiServer\Routes;

class XapiStateRoutes extends XapiRoutes
{
    /**
     * The data model.
     */
    protected $model = 'State';
    
    /**
     * Route name.
     */
    protected $routeName = 'xapi.activities.state';

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
