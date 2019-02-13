<?php

namespace Trax\XapiDesign;

use Trax\XapiDesign\Builders\StatementBuilder;

class XapiDesignServices
{
    /**
     * The App.
     */
    protected $app;
    
    /**
     * xAPI client.
     */
    protected $client;
    
    
    /**
     * Construct.
     */
    public function __construct($app, $client)
    {
        $this->app = $app;
        $this->client = $client;
    }
    
    /**
     * Get the statement builder.
     */
    public function builder() {
        return new StatementBuilder($this->client);
    }

    
}
