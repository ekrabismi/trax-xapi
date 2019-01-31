<?php

namespace Trax\XapiServer\Routes;

class XapiAboutRoutes
{
    /**
     * Router.
     */
    protected $router;
    
    /**
     * Namespace.
     */
    protected $namespace;
    
    
    /**
     * Construct.
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Register the routes.
     */
    public function register($router)
    {
        $this->router = $router;

        // About API
        if (traxRunningInLumen()) {
            $this->router->get('trax/ws/xapi/about', ['as' => 'trax.ws.xapi.about.get', 'uses' => $this->namespace.'\Http\Controllers\XapiAboutController@about']);
        } else {
            $this->router->get('trax/ws/xapi/about', $this->namespace.'\Http\Controllers\XapiAboutController@about')->name('trax.ws.xapi.about.get');
            if (config('trax.ui.enabled')) {
                $this->router->get('trax/ajax/xapi/about', $this->namespace . '\Http\Controllers\XapiAboutController@about')->name('trax.ajax.xapi.about.get');
            }
        }
    }
    

}
