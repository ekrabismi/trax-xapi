<?php

namespace Trax\XapiServer\Routes;

class XapiServerDataRoutes
{
    /**
     * Router.
     */
    protected $router;
    

    /**
     * Register the routes.
     */
    public function register($router)
    {
        $this->router = $router;

        // No Auth route with Lumen
        if (traxRunningInLumen()) return;

        // No UI config
        if (!config('trax.ui.enabled')) return;
        
        // Trax controllers
        
        $this->router->middleware(['web', 'auth', 'locale'])->namespace('Trax\XapiServer\Http\Controllers')->group(function () {
        
            // Clear data
            $this->router->post('trax/ajax/xapi-server/management/clear-all', 'XapiServerDataController@clearAll')->name('trax.ajax.xapi-server.management.clear-all');
        });
    }
    

}
