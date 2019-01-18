<?php

namespace Trax\XapiClient;

use Illuminate\Support\ServiceProvider;

class XapiClientServiceProvider extends ServiceProvider
{
    
    /**
     * Register the service provider.
     */
    public function register()
    {
        // Register services
        $this->app->singleton('Trax\XapiClient\XapiClientServices', function ($app) {
            return new XapiClientServices($app);
        });
    }

        
}
