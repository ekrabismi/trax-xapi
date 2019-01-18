<?php

namespace Trax\XapiServer\Http\Controllers;

use Illuminate\Http\Request;

use Trax\XapiServer\XapiServerServices;
use Trax\XapiServer\Http\Validations\XapiAgentValidation;
use Trax\XapiServer\Exceptions\XapiRequestException;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'XapiAgent');

class XapiAgentController extends XapiAgentControllerSwitch
{
    use XapiAgentValidation;

    
    /**
     * Construct.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->xapiAgents();
    }

    /**
     * Get data entries.
     */
    public function get(Request $request)
    {
        $this->allowsRead($request);
        $this->validateGetRequest($request);
        $res = $this->store->getOne(json_decode($request->input('agent')));
        return response()->json($res);
    }

    /**
     * Store a data entry and return its ID.
     */
    public function store(Request $request)
    {
        // Alternate request
        if ($request->has('method')) {
            if ($request->input('method') == 'GET') return $this->get($request);
        }
        throw new XapiRequestException('POST request not allowed.');
    }
    
}
