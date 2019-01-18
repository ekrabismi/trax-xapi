<?php

namespace Trax\XapiServer\Http\Controllers;

use Trax\XapiServer\XapiServerServices;
use Trax\XapiServer\Http\Validations\XapiAgentProfileValidation;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'XapiAgentProfile');

class XapiAgentProfileController extends XapiAgentProfileControllerSwitch
{
    use XapiAgentProfileValidation, XapiDocumentController;
    

    /**
     * Concurrency.
     */
    protected $concurrency = true;

    
    /**
     * Construct.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->xapiAgentProfiles();
    }


}
