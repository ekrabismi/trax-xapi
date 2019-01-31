<?php

namespace Trax\XapiServer\Routes;

class XapiStatementRoutes extends XapiRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Statement';
    
    /**
     * Route name.
     */
    protected $routeName = 'xapi.statements';

    /**
     * Only these API functions.
     */
    protected $only = array('get', 'store', 'storeOne');


    /**
     * Construct.
     */
    public function __construct($plugin, $namespace, $config)
    {
        parent::__construct($plugin, $namespace, $config);
        $this->methods['storeOne'] = ['http' => 'put', 'route' => ''];
    }
    

}
