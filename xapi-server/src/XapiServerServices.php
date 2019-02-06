<?php

namespace Trax\XapiServer; 

use Trax\DataStore\DataStoreServices;
use Trax\XapiServer\Units\XapiStoresUnit;

class XapiServerServices extends DataStoreServices
{
    /**
     * Namespace.
     */
    protected $namespace = __NAMESPACE__;
    
    /**
     * Directory. Must be overridden.
     */
    protected $dir = __DIR__;
    

    // ---------------------------------- xAPI Standard Stores ------------------------- //

    
    /**
     * Get Xapi Statements store.
     */
    public function xapiStatements() {
        return $this->store('Statement', 'Xapi');
    }

    /**
     * Get States store.
     */
    public function xapiStates() {
        return $this->store('State', 'Xapi');
    }

    /**
     * Get Actvity Profile store.
     */
    public function xapiActivityProfiles() {
        return $this->store('ActivityProfile', 'Xapi');
    }

    /**
     * Get Agent Profile store.
     */
    public function xapiAgentProfiles() {
        return $this->store('AgentProfile', 'Xapi');
    }

    /**
     * Get Activity store.
     */
    public function xapiActivities() {
        return $this->store('Activity', 'Xapi');
    }

    /**
     * Get Agent store.
     */
    public function xapiAgents() {
        return $this->store('Agent', 'Xapi');
    }


    // ---------------------------------- xAPI Standard Unit of Work ------------------------- //


    /**
     * Get Xapi Statements store.
     */
    public function xapiStores()
    {
        return new XapiStoresUnit($this);
    }


    // ---------------------------------- Direct Access Stores ------------------------- //


    /**
     * Get Statements store.
     */
    public function statements() {
        return $this->store('Statement');
    }

    /**
     * Get States store.
     */
    public function states() {
        return $this->store('State');
    }

    /**
     * Get Actvity Profile store.
     */
    public function activityProfiles() {
        return $this->store('ActivityProfile');
    }

    /**
     * Get Agent Profile store.
     */
    public function agentProfiles() {
        return $this->store('AgentProfile');
    }

    /**
     * Get Activity store.
     */
    public function activities() {
        return $this->store('Activity');
    }

    /**
     * Get Agent store.
     */
    public function agents() {
        return $this->store('Agent');
    }

    /**
     * Get Attachment store.
     */
    public function attachments() {
        return $this->store('Attachment');
    }


}
