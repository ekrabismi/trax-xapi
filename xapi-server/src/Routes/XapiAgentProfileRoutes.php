<?php

namespace Trax\XapiServer\Routes;

class XapiAgentProfileRoutes extends XapiRoutes
{
    /**
     * The data model.
     */
    protected $model = 'AgentProfile';
    
    /**
     * Route name.
     */
    protected $routeName = 'xapi.agents.profile';

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
