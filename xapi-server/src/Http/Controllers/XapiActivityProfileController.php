<?php

namespace Trax\XapiServer\Http\Controllers;

use Trax\XapiServer\XapiServerServices;
use Trax\XapiServer\Http\Validations\XapiActivityProfileValidation;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'XapiActivityProfile');

class XapiActivityProfileController extends XapiActivityProfileControllerSwitch
{
    use XapiActivityProfileValidation, XapiDocumentController;


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
        $this->store = $this->services->xapiActivityProfiles();
    }

    
}
