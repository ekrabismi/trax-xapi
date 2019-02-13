<?php

namespace Trax\XapiDesign;

use Trax\Foundation\TraxServiceProvider;

class XapiDesignServiceProvider extends TraxServiceProvider
{
    /**
     * Plugin code. 
     */
    protected $plugin = 'trax-xapi-design';

    /**
     * Namespace. Must be overridden.
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Directory. Must be overridden.
     */
    protected $dir = __DIR__;
    

    /**
     * Register services.
     */
    protected function registerServices()
    {
        // Registration
        $this->app->singleton('Trax\XapiDesign\XapiDesignServices', function ($app) {
            $xapiClient = $app->make('Trax\XapiClient\XapiClientServices');
            return new XapiDesignServices($app, $xapiClient);
        });
    }

}

