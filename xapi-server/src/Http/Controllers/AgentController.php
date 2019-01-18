<?php

namespace Trax\XapiServer\Http\Controllers;

use Trax\XapiServer\XapiServerServices;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'Agent');

class AgentController extends AgentControllerSwitch
{
    
    /**
     * Construct.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->datatableOptions = ['filteredCount' => true];
        $this->services = $services;
        $this->store = $this->services->agents();
    }

    
}
