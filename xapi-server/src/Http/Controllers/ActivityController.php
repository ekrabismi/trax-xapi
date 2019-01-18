<?php

namespace Trax\XapiServer\Http\Controllers;

use Trax\XapiServer\XapiServerServices;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'Activity');

class ActivityController extends ActivityControllerSwitch
{
    
    /**
     * Construct.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->datatableOptions = ['filteredCount' => true];
        $this->services = $services;
        $this->store = $this->services->activities();
    }

    
}
