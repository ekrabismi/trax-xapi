<?php

namespace Trax\XapiClient;

class XapiClientServices
{
    /**
     * The App.
     */
    protected $app;
    
    
    /**
     * Construct.
     */
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    /**
     * Get the Statement API.
     */
    public function statements() {
        return new XapiStatementApi();
    }
    
    /**
     * Get the State API.
     */
    public function states() {
        return new XapiStateApi();
    }
    
    /**
     * Get the Activity Profile API.
     */
    public function activityProfiles() {
        return new XapiActivityProfileApi();
    }
    
    /**
     * Get the Agent Profile API.
     */
    public function agentProfiles() {
        return new XapiAgentProfileApi();
    }
    
    /**
     * Get the Activity API.
     */
    public function activities() {
        return new XapiActivityApi();
    }
    
    /**
     * Get the Agent API.
     */
    public function agents() {
        return new XapiAgentApi();
    }
    
    /**
     * Get the About API.
     */
    public function about() {
        return new XapiAboutApi();
    }

}
