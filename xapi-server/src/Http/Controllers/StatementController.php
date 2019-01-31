<?php

namespace Trax\XapiServer\Http\Controllers;

use Trax\XapiServer\XapiServerServices;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'Statement');

class StatementController extends StatementControllerSwitch
{
    
    /**
     * Construct.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->datatableOptions = ['filteredCount' => true];
        $this->services = $services;
        $this->store = $this->services->statements();
    }
    

}
