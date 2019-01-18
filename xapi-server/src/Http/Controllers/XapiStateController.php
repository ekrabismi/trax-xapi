<?php

namespace Trax\XapiServer\Http\Controllers;

use Trax\XapiServer\XapiServerServices;
use Trax\XapiServer\Http\Validations\XapiStateValidation;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'XapiState');

class XapiStateController extends XapiStateControllerSwitch
{
    use XapiStateValidation, XapiDocumentController;
    
    
    /**
     * Concurrency.
     */
    protected $concurrency = false;

    
    /**
     * Construct.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->xapiStates();
    }


}
