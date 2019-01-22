<?php

namespace Trax\XapiServer;

use Illuminate\Contracts\Debug\ExceptionHandler;

use Trax\DataStore\DataStoreServiceProvider;
use Trax\XapiServer\Routes\XapiStatementRoutes;
use Trax\XapiServer\Routes\XapiStateRoutes;
use Trax\XapiServer\Routes\XapiAgentRoutes;
use Trax\XapiServer\Routes\XapiAgentProfileRoutes;
use Trax\XapiServer\Routes\XapiActivityRoutes;
use Trax\XapiServer\Routes\XapiActivityProfileRoutes;
use Trax\XapiServer\Routes\XapiAboutRoutes;
use Trax\XapiServer\Routes\XapiServerDataRoutes;
use Trax\XapiServer\Validators\XapiDataValidator;
use Trax\XapiServer\Exceptions\XapiServerExceptionHandler;

class XapiServerServiceProvider extends DataStoreServiceProvider
{
    /**
     * Plugin code. 
     */
    protected $plugin = 'trax-xapi-server';

    /**
     * Plugin dependencies. May be overridden.
     */
    protected $dependencies = ['trax-account'];

    /**
     * Namespace. Must be overridden.
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Directory. Must be overridden.
     */
    protected $dir = __DIR__;

    /**
     * Register UI.
     */
    protected $hasUI = true;
    
    /**
     * Register helpers.
     */
    protected $hasHelpers = true;
    
    /**
     * Models > Tables.
     */
    protected $models = [
        'Statement' => 'trax_xapiserver_statements',
        'State' => 'trax_xapiserver_states',
        'ActivityProfile' => 'trax_xapiserver_activity_profiles',
        'AgentProfile' => 'trax_xapiserver_agent_profiles',
        'Activity' => 'trax_xapiserver_activities',
        'Agent' => 'trax_xapiserver_agents',
        'Attachment' => 'trax_xapiserver_attachments',
    ];
    
    /**
     * Ajax models.
     */
    protected $ajaxModels = [
        'Statement' => 'statements',
        'Activity' => 'activities',
        'Agent' => 'agents',
    ];
    
    /**
     * The service provider middlewares. Must be declared manually in Lumen!
     */
    protected $middlewares = [
        'xapi' => \Trax\XapiServer\Http\Middleware\XapiMiddleware::class,
        'xapi.statement' => \Trax\XapiServer\Http\Middleware\XapiStatementMiddleware::class,
    ];

    
    /**
     * Register services.
     */
    protected function registerServices()
    {
        $plugin = $this->plugin;
        $models = $this->models;
        $this->app->singleton('Trax\XapiServer\XapiServerServices', function ($app) use($plugin, $models) {
            return new XapiServerServices($app, $plugin, $models);
        });
    }

    /**
     * Register routes.
     */
    protected function registerRoutes($models = null)
    {
        // AJAX routes
        parent::registerRoutes($this->ajaxModels);

        $config = config('trax-xapi-server.stores.Statement');

        // xAPI routes for stores
        
        (new XapiStatementRoutes(
            $this->plugin,
            $this->namespace,
            config('trax-xapi-server.stores.Statement')
        ))->register($this->router);

        (new XapiStateRoutes(
            $this->plugin,
            $this->namespace,
            config('trax-xapi-server.stores.State')
        ))->register($this->router);

        (new XapiAgentRoutes(
            $this->plugin,
            $this->namespace,
            config('trax-xapi-server.stores.Agent')
        ))->register($this->router);

        (new XapiAgentProfileRoutes(
            $this->plugin,
            $this->namespace,
            config('trax-xapi-server.stores.AgentProfile')
        ))->register($this->router);

        (new XapiActivityRoutes(
            $this->plugin,
            $this->namespace,
            config('trax-xapi-server.stores.Activity')
        ))->register($this->router);

        (new XapiActivityProfileRoutes(
            $this->plugin,
            $this->namespace,
            config('trax-xapi-server.stores.ActivityProfile')
        ))->register($this->router);

        // Not a store
        (new XapiAboutRoutes($this->namespace))->register($this->router);

        // Other data routes
        (new XapiServerDataRoutes)->register($this->router);
    }

    /**
     * Register additional validation rules.
     */
    protected function registerValidationRules()
    {
        (new XapiDataValidator($this->app))->register();
    }

    /**
     * Register a custom exception handler.
     */
    protected function registerExceptionHandler()
    {
        $this->app->bind(ExceptionHandler::class, XapiServerExceptionHandler::class);
    }
    

}
