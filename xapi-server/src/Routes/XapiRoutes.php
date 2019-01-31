<?php

namespace Trax\XapiServer\Routes;

use Trax\DataStore\Routes\DataRoutes;

class XapiRoutes extends DataRoutes
{
    /**
     * Middlewares.
     */
    protected $middlewares = array('xapi');

    /**
     * Controller prefix.
     */
    protected $controllerPrefix = 'Xapi';

    /**
     * Register the routes.
     */
    public function register($router)
    {
        $this->router = $router;
        if (isset($this->config['xapi']) && $this->config['xapi']) $this->registerWsApi();
    }
    

}
